@extends('master')
@section('content')
<div class="container">

    @if (session('success'))
    @alert(['type' => 'success','title'=>'Success'])
        {{ session('success') }}
    @endalert
    @endif

    <div class="row justify-content-left">
        <div class="card-header">Foto</div>
        <div class="card">
            <div class="card-body">
                <img src="{{ Auth::user()->profile_photo }}" alt="Foto perfil">
        </div>
    </div>
    <div class="row justify-content-rigth">
        <div class="card-header">Nome</div>
        <div class="card">
            <div class="card-body">
                {{ Auth::user()->name }}
            </div>
        </div>
    </div>
</div>
@endsection
