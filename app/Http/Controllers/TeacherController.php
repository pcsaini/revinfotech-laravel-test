<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Http\Requests\TeacherRequest;
use App\Student;
use App\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $teachers = Teacher::with('students')->select(['teachers.*']);
            return DataTables::eloquent($teachers)
                ->editColumn('id', function ($teacher) {
                    return '<a href="' . route('teachers.view', $teacher->id) . '">'. $teacher->id .'</a>';
                })
                ->editColumn('name', function ($teacher) {
                    return '<a href="' . route('teachers.view', $teacher->id) . '">'. $teacher->name .'</a>';
                })
                ->addColumn('students', function ($teacher) {
                    $students = $teacher->students;

                    Log::info($students);

                    $html = '';

                    foreach ($students as $student) {
                        $html .= '<a href="' . route('students.view', $student->id) . '" class="badge badge-primary mr-1">'. $student->name .'</a>';
                    }

                    return $html;
                })
                ->addColumn('image', function ($teacher) {
                    if (!empty($teacher->image)) {
                        return '<img src="'. Storage::url(config('constants.teacher_thumb_path'). $teacher->image) .'" alt="'. $teacher->name .'" height="50" width="50">';
                    } else {
                        return '<img src="'. asset('img/default.png') .'" alt="'. $teacher->name .'" height="50" width="50">';
                    }
                })
                ->addColumn('action', function ($teacher) {
                    $edit = '<a href="' . route('teachers.edit', $teacher->id) . '" class="badge badge-primary">Edit</a>';
                    $delete = '<a href="' . route('teachers.delete', $teacher->id) . '" class="badge badge-danger" onclick="return confirm(\'Are you sure?, You want to delete this user?\')">Delete</a>';

                    return $edit . ' ' . $delete;
                })
                ->rawColumns(['id', 'name', 'students', 'action', 'image'])
                ->make(true);
        } else {
            return view('teachers.index');
        }
    }

    public function create() {
        return view('teachers.create');
    }

    public function edit($id) {
        $teacher = Teacher::find($id);

        if (!$teacher) {
            return back()->with('error', 'Teacher not found');
        }

        return view('teachers.create', ['teacher' => $teacher]);
    }

    /**
     * save the student record
     *
     * @param StudentRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(TeacherRequest $request) {

        $data = $request->only(['name', 'email', 'phone_number', 'gender']);

        // save image
        if ($request->hasFile('image')) {
            try {
                $image = $request->file('image');
                $extension = $image->getClientOriginalExtension();
                $name = Str::slug($request->name) . '-' . time() . '.' . $extension;

                $thumbPath = config('constants.teacher_thumb_path');
                $path = config('constants.teacher_path');
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
            Teacher::where('id', $request->id)->update(array_filter($data));
            $message = 'Teacher updated successfully';
        } else {
            Teacher::create($data);
            $message = 'Teacher created successfully';
        }
        DB::commit();

        return redirect()->route('teachers.index')->with(['success' => $message]);

    }

    public function delete($id) {
        $teacher = Teacher::find($id);

        if (!$teacher) {
            return back()->with('error', 'Teacher not found');
        }

        $thumbPath = config('constants.teacher_thumb_path');
        $path = config('constants.teacher_path');
        if (!empty($teacher->image)) {
            if (Storage::exists($thumbPath. $teacher->image)) {
                Storage::delete($thumbPath. $teacher->image);
            }

            if (Storage::exists($path. $teacher->image)) {
                Storage::delete($path. $teacher->image);
            }
        }

        $teacher->delete();

        return redirect()->route('teachers.index')->with(['success' => 'Teacher deleted successfully']);
    }

    public function view($id) {
        $teacher = Teacher::with('students')->find($id);

        if (!$teacher) {
            return back()->with('error', 'Teacher not found');
        }

        return view('teachers.view', ['teacher' => $teacher]);
    }
}
