@extends('master')
@section('content')
    @if ($errors->any())
    @alert(['type' => 'danger','title'=>'Errors'])
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endalert
    @endif
<form method="POST" action="{{ route('me.password') }}">
    @method('patch')
    @csrf
    <div div class="form-group">
        <label for="oldPassword" class="col-sm-4 col-form-label"> Current Password</label>
        <div class="col-sm-10">
            <input type="password" name="old_password" class="form-control" id="oldPassword" placeholder="Current Password">
        </div>
    </div>
    
    <div div class="form-group">
        <label for="newPassword" class="col-sm-4 col-form-label"> New Password</label>
        <div class="col-sm-10">
            <input type="password" name="password" class="form-control" id="newPassword" placeholder="New Password">
        </div>
    </div>

    <div div class="form-group">
        <label for="passwordConfirmation" class="col-sm-4 col-form-label"> Password Confirmation</label>
        <div class="col-sm-10">
            <input type="password" name="password_confirmation" class="form-control" id="passwordConfirmation" placeholder="Re-enter New Password" aria-describedby="emailHelp">
        </div>
        <small id="passwordHelp" class="form-text text-muted col-sm-offset-5 col-sm-6">The 'Confirmation Password' must be equal to 'New Password'</small>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-5 col-sm-6">
            <button type="submit" class="btn btn-outline-primary" name="ok">Submit</button>
            <button type="submit" class="btn btn-outline-primary" name="cancel">Cancel</button>
        </div>
    </div>
</form>
@endsection


