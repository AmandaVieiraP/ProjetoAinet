@extends('master')

@section('content')
<div class="container">

    @if (Session::has('success'))
    @alert(['type' => 'success','title'=>'Success!'])
        {{ session('success') }}
    @endalert
    @endif

    @if (auth()->user()->profile_photo)
        <img src="{{ asset('storage/profiles/' . auth()->user()->profile_photo) }}" class="img-fluid img-thumbnail float-left" alt="Foto Perfil" width="200" height="200">
    @else
        <img src="{{ asset('storage/profiles/avatar.jpg') }}" alt="Foto Perfil" width="200" height="200">
    @endif
    <h5>&nbsp;Name: &nbsp;{{ Auth::user()->name }}</h5>

</div>
@endsection
