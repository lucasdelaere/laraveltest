@extends('layouts.admin')
@section('title')
    Create User
@endsection
@section('content')
    <h1>Create User</h1>
    <hr>
    @include('includes.form_error') <!-- validation -->
    {!! Form::open(['method'=>'POST', 'action'=>'App\Http\Controllers\AdminUsersController@store', 'files'=>true]) !!}
    <div class="form-group">
        {!! Form::label('name', 'Name:') !!}
        {!! Form::text('name', null, ['placeholder'=>'Name', 'class'=>'form-control']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('email', 'E-mail:') !!}
        {!! Form::text('email', null, ['class'=>'form-control', 'placeholder' => 'Email']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('Select roles: (houd CTRL ingedrukt om meerdere te selecteren') !!}
        {!! Form::select('roles[]', $roles,null,['class'=>'form-control', 'multiple'=>'multiple']) !!} <!-- value, innerHTML, default value (if nothing chosen), [classes]-->

    </div>
    <div class="form-group">
        {!! Form::label('is_active', 'Status:') !!}
        {!! Form::select('is_active',array(1=>'Active', 0=>'Not Active'), 0, ['class'=>'form-control']) !!}
    </div> <!-- 0 ('Non Active') is default -->
    <div class="form-group">
        {!! Form::label('password','Password:') !!}
        {!! Form::password('password',['class'=>'form-control', 'placeholder' => 'Password required...']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('photo_id', 'Photo_id:') !!}
        {!! Form::file('photo_id', null, ['class'=>'form-control']) !!}
    </div>
    <div class="form-group">
        {!! Form::submit('Create User', ['class'=>'btn btn-primary']) !!}
    </div>
    {!! Form::close() !!}
@endsection
