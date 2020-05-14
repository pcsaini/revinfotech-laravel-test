<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Student;
use App\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $students = Student::with(['teacher' => function($q) {
                $q->select('id', 'name');
            }])->select(['students.*'])->orderByDesc('created_at');

            if (isset($request->teacher_id) && !empty($request->teacher_id)) {
                $students = $students->where('teacher_id', $request->teacher_id);
            }

            return DataTables::eloquent($students)
                ->editColumn('id', function ($student) {
                    return '<a href="' . route('students.view', $student->id) . '">'. $student->id .'</a>';
                })
                ->editColumn('name', function ($student) {
                    return '<a href="' . route('students.view', $student->id) . '">'. $student->name .'</a>';
                })
                ->addColumn('teacher', function ($student) {
                    return '<a href="' . route('teachers.view', $student->teacher->id) . '" class="badge badge-primary">' . $student->teacher->name . '</a>';
                })
                ->addColumn('image', function ($student) {
                    if (!empty($student->image)) {
                        return '<img src="'. Storage::url(config('constants.student_thumb_path'). $student->image) .'" alt="'. $student->name .'" height="50" width="50">';
                    } else {
                        return '<img src="'. asset('img/default.png') .'" alt="'. $student->name .'" height="50" width="50">';
                    }
                })
                ->addColumn('action', function ($student) {
                    $edit = '<a href="' . route('students.edit', $student->id) . '" class="badge badge-primary">Edit</a>';
                    $delete = '<a href="' . route('students.delete', $student->id) . '" class="badge badge-danger" onclick="return confirm(\'Are you sure?, You want to delete this user?\')">Delete</a>';

                    return $edit . ' ' . $delete;
                })
                ->rawColumns(['id', 'name', 'teacher', 'action', 'image'])
                ->make(true);
        } else {
            return view('students.index');
        }
    }

    public function create() {
        $teachers = Teacher::select(['id', 'name' , 'email'])->get();
        return view('students.create', ['teachers' => $teachers]);
    }

    public function edit($id) {
        $student = Student::find($id);

        if (!$student) {
            return back()->with('error', 'Student not found');
        }

        $teachers = Teacher::select(['id', 'name' , 'email'])->get();

        return view('students.create', ['teachers' => $teachers, 'student' => $student]);
    }

    /**
     * save the student record
     *
     * @param StudentRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(StudentRequest $request) {

        $data = $request->only(['name', 'email', 'phone_number', 'gender', 'teacher_id']);

        // save image
        if ($request->hasFile('image')) {
            try {
                $image = $request->file('image');
                $extension = $image->getClientOriginalExtension();
                $name = Str::slug($request->name) . '-' . time() . '.' . $extension;

                $thumbPath = config('constants.student_thumb_path');
                $path = config('constants.student_path');
                if (Image::make($image)->height() > Image::make($image)->width()) {
                    $thumb = Image::make($image)->resize(100, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->crop(100, 100)->encode();
                } else {
                    $thumb = Image::make($image)->resize(null, 100, function ($constraint) {
                        $constraint->aspectRatio();
                    })->crop(100, 100)->encode($extension);
                }

                Storage::put($thumbPath . $name, (string)$thumb, 'public');
                Storage::put($path . $name, file_get_contents($image), 'public');

                $data['image'] = $name;

                // remove previous image
                if (isset($request->hidden_image) && !empty($request->hidden_image)) {
                    if (Storage::exists($thumbPath. $request->hidden_image)) {
                        Storage::delete($thumbPath. $request->hidden_image);
                    }

                    if (Storage::exists($path. $request->hidden_image)) {
                        Storage::delete($path. $request->hidden_image);
                    }
                }
            } catch (\Exception $e) {
                return back()->with('error', 'Error to upload image');
            }
        }


        // save student record
        DB::beginTransaction();
        if (isset($request->id) && !empty($request->id)){
            Student::where('id', $request->id)->update(array_filter($data));
            $message = 'Student updated successfully';
        } else {
            Student::create($data);
            $message = 'Student created successfully';
        }
        DB::commit();

        return redirect()->route('students.index')->with(['success' => $message]);

    }

    public function delete($id) {
        $student = Student::find($id);

        if (!$student) {
            return back()->with('error', 'Student not found');
        }

        $thumbPath = config('constants.student_thumb_path');
        $path = config('constants.student_path');
        if (!empty($student->image)) {
            if (Storage::exists($thumbPath. $student->image)) {
                Storage::delete($thumbPath. $student->image);
            }

            if (Storage::exists($path. $student->image)) {
                Storage::delete($path. $student->image);
            }
        }

        $student->delete();

        return redirect()->route('students.index')->with(['success' => 'Student deleted successfully']);
    }

    public function view($id) {
        $student = Student::with('teacher')->find($id);

        if (!$student) {
            return back()->with('error', 'Student not found');
        }

        return view('students.view', ['student' => $student]);
    }
}
