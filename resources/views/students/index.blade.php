@extends('layouts.main')

@section('content')

    <div class="card">
        <div class="card-header">
            Students

            <div class="float-right">
                <a class="btn btn-sm btn-success" href="{{ route('students.create') }}">Add Student</a>
            </div>
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
        window.students_list = "{{ route('students.index') }}"
    </script>

    <script src="{{ asset('js/custom/students/index.js') }}"></script>
@endsection
