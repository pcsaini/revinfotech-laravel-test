@extends('layouts.main')

@section('content')

    <div class="card">
        <div class="card-header">
            Teacher : {{ $teacher->name }}
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <p><strong>Name: </strong> {{ $teacher->name}}</p>
                    <p><strong>Email: </strong> {{ $teacher->email}}</p>
                    <p><strong>Phone Number: </strong> {{ $teacher->phone_number}}</p>
                    <p><strong>Gender: </strong> {{ ucfirst($teacher->gender)}}</p>
                </div>
                <div class="col-6">
                    @if(!empty($teacher->image))
                        <img src="{{ \Illuminate\Support\Facades\Storage::url(config('constants.teacher_path'). $teacher->image) }}" height="200" width="auto" alt="{{ $teacher->name }}">
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            {{ $teacher->name }}'s Students
        </div>
        <div class="card-body">
            <table id="studentTable" class="table table-striped table-bordered" style="width:100%">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Image</th>
                    <th>Teacher</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/dataTables.bootstrap4.min.css') }}">
@endsection

@section('js')
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>

    <script>
        window.students_list = "{{ route('students.index', ['teacher_id' => $teacher->id]) }}"
    </script>

    <script src="{{ asset('js/custom/students/index.js') }}"></script>
@endsection
