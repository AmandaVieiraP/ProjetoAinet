@extends('master')

@section('content')
<div class="container">

    @if (Session::has('success'))
    @alert(['type' => 'success','title'=>'Success!'])
        {{ session('success') }}
    @endalert
    @endif

    <nav class="navbar navbar-expand-lg navbar-light bg-light
    ">

      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li>
            <a class="nav-link" href="{{ route('me.profileForm') }}">Update Profile</a>
          </li>
          <li>
            <a class="nav-link" href="{{ route('me.passwordForm') }}">Change Password</a>
          </li>
          <li>
            <a class="nav-link" href="{{ route('users.profiles') }}">View Other Profiles</a>
          </li>
          <li>
            <a class="nav-link" href="{{ route('users.associates') }}"> View My Associates</a>
          </li>
          <li>
            <a class="nav-link" href="{{ route('me.associateOf') }}"> View Associates-Of</a>
          </li>
        </ul>
      </div>
    </nav>

    <br>


    <div class="card text-center">
      <div class="card-header">
        <span>&nbsp;Member since &nbsp;{{ Auth::user()->created_at->format('F Y') }}</span>
      </div>
      <div class="card-body">
        <h5 class="card-title">&nbsp;Name: &nbsp;{{ Auth::user()->name }}</h5>
        @if (auth()->user()->profile_photo)
            <img src="{{ asset('storage/profiles/' . auth()->user()->profile_photo) }}" class="img-thumbnail" alt="Foto Perfil" width="150" height="170">
        @else
            <img src="{{ asset('storage/profiles/avatar.jpg') }}" class="img-thumbnail" alt="Foto Perfil" width="150" height="150">
        @endif
        <p class="card-text">&nbsp;E-mail: &nbsp;{{ Auth::user()->email }}</p>
        
      </div>
      <div class="card-footer text-muted">
        <p class="font-weight-light">&nbsp;Last updated &nbsp;{{ Auth::user()->updated_at->diffForHumans() }}</p>
      </div>
    </div>
</div>
@endsection
