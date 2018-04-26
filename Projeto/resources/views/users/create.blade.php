@extends('users.master')
@section('content')
<form action="{{ action('UserController@store') }}" method="post" class="form-group">
{{csrf_field()}}
<div class="form-group">
    <label for="inputFullname">Name</label>
    <input
        type="text" class="form-control"
        name="name" id="inputFullname"
        placeholder="Fullname" value="{{ escape(old('name')) }}" />
</div>
<div class="form-group">
    <label for="inputType">Type</label>
    <select name="type" id="inputType" class="form-control">
        <option disabled selected> -- select an option -- </option>
        <option {{ is_selected(old('type'), '0') }} value="0">Administrator</option>
        <option {{ is_selected(old('type'), '1') }} value="1">Publisher</option>
        <option {{ is_selected(old('type'), '2') }} value="2">Client</option>
    </select>
</div>
<div class="form-group">
    <label for="inputEmail">Email</label>
    <input
        type="email" class="form-control"
        name="email" id="inputEmail"
        placeholder="Email address" value="{{ escape(old('email')) }}"/>
</div>
    <div class="form-group">
        <label for="inputPassword">Password</label>
        <input
            type="password" class="form-control"
            name="password" id="inputPassword"
            value="{{ escape(old('password')) }}"/>
    </div>
    <div class="form-group">
        <label for="inputPasswordConfirmation">Password confirmation</label>
        <input
            type="password" class="form-control"
            name="password_confirmation" id="inputPasswordConfirmation"/>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-success" name="ok">Add</button>
        <button type="submit" class="btn btn-default" name="cancel">Cancel</button>
    </div>
</form>

@endsection('content') 

