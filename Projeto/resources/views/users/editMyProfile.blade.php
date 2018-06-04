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

<form method="POST" action="{{ route('me.profile') }}" enctype="multipart/form-data">
    @method('put')
    @csrf
    <div class="form-group">
        <label for="userName" class="col-sm-4 col-form-label"> Name</label>
        <div class="col-sm-10">
            <input type="text" name="name" class="form-control" id="userName" placeholder="Name" value="{{auth()->user()->name}}">
        </div>
    </div>
    
    <div class="form-group">
        <label for="userPhone" class="col-sm-4 col-form-label"> Phone</label>
        <div class="col-sm-10">
            <input type="text" name="phone" class="form-control" id="userPhone" placeholder="Phone Number" value="{{auth()->user()->phone}}">
        </div>
    </div>

    <div class="form-group">
        <label for="userEmail" class="col-sm-4 col-form-label"> Email</label>
        <div class="col-sm-10">
            <input type="email" name="email" class="form-control" id="userEmail" placeholder="Email" value="{{auth()->user()->email}}">
        </div>
    </div>

    <div class="form-group">
        <label for="userPhoto" class="col-sm-4 col-form-label">Upload Profile Photo</label>
        <input type="file" name="profile_photo" id="userPhoto" class="form-control" accept=".jpg,.jpeg,.png">
    </div>

    <div class="form-group">
        <div class="col-sm-offset-5 col-sm-6">
            <button type="submit" class="btn btn-outline-primary" name="ok">Submit</button>
            <button type="submit" class="btn btn-outline-primary" name="cancel">Cancel</button>
        </div>
    </div>
</form>
@endsection