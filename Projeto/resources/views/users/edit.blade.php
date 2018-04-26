@extends('users.master')
@section('content')
<form action="{{ action('UserController@update', $user->id) }}" method="post" class="form-group">
	@method('put')
    @csrf
    <input type="hidden" name="user_id" value="{{ $user->user_id }}" />
    
    <div class="form-group">
    <label for="inputFullname">Fullname</label>
    <input
        type="text" class="form-control"
        name="name" id="inputFullname"
        placeholder="Fullname" value="{{ escape(old('name', $user->name)) }}" />
        @if ($errors->has('name'))
            <em>{{ $errors->first('name') }}</em>
        @endif
</div>
<div class="form-group">
    <label for="inputType">Type</label>
    <select name="type" id="inputType" class="form-control">
        <option disabled selected> -- select an option -- </option>
        <option {{ is_selected(old('user_type', strval($user->type)), '0') }} value="0">Administrator</option>
        <option {{ is_selected(old('user_type', strval($user->type)), '1') }} value="1">Publisher</option>
        <option {{ is_selected(old('user_type', strval($user->type)), '2') }} value="2">Client</option>
    </select>
    @if ($errors->has('type'))
        <em>{{ $errors->first('type') }}</em>
    @endif
</div>
<div class="form-group">
    <label for="inputEmail">Email</label>
    <input
        type="email" class="form-control"
        name="email" id="inputEmail"
        placeholder="Email address" value="{{ escape(old('email', $user->email)) }}"/>
        @if ($errors->has('email'))
            <em>{{ $errors->first('email') }}</em>
        @endif
</div>
    <div class="form-group">
        <button type="submit" class="btn btn-success" name="ok">Save</button>
        <button type="submit" class="btn btn-default" name="cancel">Cancel</button>
    </div>
</form>
@endsection('content')
