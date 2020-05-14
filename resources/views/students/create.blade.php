@extends('layouts.main')

@section('content')

    <div class="card">
        <div class="card-header">
            {{ isset($student) && !empty($student) ? 'Edit' : 'Create' }} Students

        </div>
        <div class="card-body">

            <form role="form" method="post" action="{{ route('students.save') }}" enctype="multipart/form-data" data-parsley-validate>
                @csrf
                <input type="hidden" name="id" value="{{ isset($student) && !empty($student) ? $student->id : 0 }}">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" placeholder="Enter Name" name="name"
                               data-parsley-required="true"
                               data-parsley-required-message="Please enter name"
                               data-parsley-trigger="focusout"
                               value="{{ old('name') ?? $student['name'] ?? ''  }}">
                        @if ($errors->has('name'))
                            <div class="invalid-feedback fadeIn" style="display: block;">{{ $errors->first('name') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-6">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" placeholder="Enter Email" name="email"
                               data-parsley-required="true"
                               data-parsley-required-message="Please enter email"
                               data-parsley-error-message="Please enter valid email"
                               data-parsley-trigger="focusout"
                               value="{{ old('email') ??  $student['email'] ?? '' }}">
                        @if ($errors->has('email'))
                            <div class="invalid-feedback fadeIn" style="display: block;">{{ $errors->first('email') }}</div>
                        @endif
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="phone_number">Phone Number</label>
                        <input type="number" class="form-control" id="phone_number" placeholder="Enter Phone Number"
                               name="phone_number"
                               data-parsley-required="true"
                               data-parsley-length="[10, 13]"
                               data-parsley-length-message="Please enter min 10 to 13 digit phone number"
                               data-parsley-required-message="Please enter phone number"
                               data-parsley-trigger="focusout"
                               value="{{ old('phone_number') ?? $student['phone_number'] ?? '' }}">
                        @if ($errors->has('phone_number'))
                            <div class="invalid-feedback fadeIn" style="display: block;">{{ $errors->first('phone_number') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-6">
                        <label for="gender">Gender</label>
                        <select class="form-control" id="gender" name="gender" data-parsley-required="true"
                                data-parsley-required-message="Please select gender"
                                data-parsley-trigger="focusout">
                            <option disabled selected>Select Gender</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : isset($student) && $student['gender'] == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : isset($student) && $student['gender'] == 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                        @if ($errors->has('gender'))
                            <div class="invalid-feedback fadeIn" style="display: block;">{{ $errors->first('gender') }}</div>
                        @endif
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="teachers">Select Teachers</label>
                        <select class="form-control js-example-responsive" id="teachers"
                                name="teacher_id" data-parsley-required="true"
                                data-parsley-required-message="Please select teacher"
                                data-parsley-trigger="focusout">
                            <option disabled selected>Select Teacher</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ isset($student) && !empty($student) ? $student['teacher_id'] == $teacher->id ? 'selected' : '' : old('teacher_id') == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }} ({{ $teacher->email }})</option>
                            @endforeach
                        </select>
                        @if ($errors->has('teacher_id'))
                            <div class="invalid-feedback fadeIn" style="display: block;">{{ $errors->first('teacher_id') }}</div>
                        @endif
                    </div>
                </div>


                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="image">Image</label>
                        <input type="file" accept="image/*" class="form-control" name="image" id="image">
                        @if ($errors->has('image'))
                            <div class="invalid-feedback fadeIn" style="display: block;">{{ $errors->first('image') }}</div>
                        @endif
                    </div>
                </div>

                @if(isset($student->image) && !empty($student->image))
                    <input type="hidden" name="hidden_image" value="{{ $student->image }}">
                @endif

                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a href="{{ route('students.index') }}" class="btn btn-danger">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/select2.min.css') }}">
@endsection

@section('js')
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script src="{{ asset('js/parsley.min.js') }}"></script>
    <script>
        /*$("#teachers").select2({
            placeholder: 'Select Teacher',
        });*/
    </script>
@endsection
