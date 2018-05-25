@extends('master')
@section('content')


@if (count($users))
<table class="table table-striped">
    <thead>
        <tr>
            <th>Photo</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Link</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($users as $user)    
        <tr>
            <td>
                <div class="float-left"> 
                    @if (auth()->user()->profile_photo)
                        <div class="float_left">
                            <img src="{{ asset('storage/profiles/' . $user->profile_photo) }}" width="50" height="60" alt="Foto Perfil">
                        </div>
                    @else 
                         <div class="float_left">
                        <img src="{{ asset('storage/profiles/avatar.jpg') }}" width="50" height="60" alt="Foto Perfil">
                        </div>
                    @endif
                </div>
            </td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->phone }}</td>
            <td><a href="{{ url('/accounts/'.$user->id) }}">{{$user->name}}</a></td>
        </tr>
    @endforeach
    </table>

@else 
    <h2>No users found</h2>

@endif
@endsection('content')