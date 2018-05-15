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
        </tr>
    </thead>
    <tbody>
    @foreach ($users as $user)    
        <tr>
        	<td>
        		@if (auth()->user()->profile_photo)
        			<img src="{{ asset('storage/profiles/' . $user->profile_photo) }}" class="img-fluid img-thumbnail float-left" alt="Foto Perfil">
			    @else
			        <img src="{{ asset('storage/profiles/avatar.jpg') }}" alt="Foto Perfil">
			    @endif
        	</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->phone }}</td>
        </tr>
    @endforeach
    </table>

@else 
    <h2>No users found</h2>

@endif
@endsection('content')