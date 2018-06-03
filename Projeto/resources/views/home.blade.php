@extends('master')

@section('content')
<div class="container">

    @if (Session::has('success'))
    @alert(['type' => 'success','title'=>'Success!'])
        {{ session('success') }}
    @endalert
    @endif

    @include('users.partials.menuProfile')

    <div class="card text-center">
      <div class="card-header">
        <span>&nbsp;Member since &nbsp;{{ Auth::user()->created_at->format('F Y') }}</span>
      </div>
      <div class="card-body">
        <h5 class="card-title">{{ Auth::user()->name }}</h5>
        @if (auth()->user()->profile_photo)
            <img src="{{ asset('storage/profiles/' . auth()->user()->profile_photo) }}" class="img-thumbnail" alt="Foto Perfil" width="150" height="170">
        @else
            <img src="{{ asset('storage/profiles/avatar.jpg') }}" class="img-thumbnail" alt="Foto Perfil" width="150" height="150">
        @endif
        <p class="card-text">{{ Auth::user()->email }}</p>
        
      </div>
      <div class="card-footer text-muted">
        <p class="font-weight-light">&nbsp;Last updated &nbsp;{{ Auth::user()->updated_at->diffForHumans() }}</p>
      </div>
    </div>
</div>
@endsection
