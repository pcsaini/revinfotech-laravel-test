@extends('layouts.main')

@section('content')

    <div class="card">
        <div class="card-header">
            Students : {{ $student->name }}
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <p><strong>Name: </strong> {{ $student->name}}</p>
                    <p><strong>Email: </strong> {{ $student->email}}</p>
                    <p><strong>Phone Number: </strong> {{ $student->phone_number}}</p>
                    <p><strong>Gender: </strong> {{ ucfirst($student->gender)}}</p>
                    <p><strong>Teacher: </strong> <a href="{{ route('teachers.view', $student->teacher_id) }}">{{ ucfirst($student->teacher->name)}}</a></p>
                </div>
                <div class="col-6">
                    @if(!empty($student->image))
                        <img src="{{ \Illuminate\Support\Facades\Storage::url(config('constants.student_path'). $student->image) }}" height="200" width="auto" alt="{{ $student->name }}">
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
