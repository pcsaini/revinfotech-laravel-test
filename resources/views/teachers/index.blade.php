@extends('layouts.main')

@section('content')

    <div class="card">
        <div class="card-header">
            Teachers

            <div class="float-right">
                <a class="btn btn-sm btn-success" href="{{ route('teachers.create') }}">Add Teacher</a>
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
                    <th>Students</th>
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
        window.teachers_list = "{{ route('teachers.index') }}"
    </script>

    <script src="{{ asset('js/custom/teachers/index.js') }}"></script>
@endsection
