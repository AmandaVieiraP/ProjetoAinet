@extends('layouts.app')

@section('content')
<div class="container">
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
