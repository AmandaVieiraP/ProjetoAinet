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
            <th>Actions</th>

        </tr>
    </thead>
    <tbody>
    @foreach ($users as $user)    
        <tr>
        	<td>
        		@if (auth()->user()->profile_photo)
                    <div class="float-left">
        			     <img src="{{ asset('storage/profiles/' . $user->profile_photo) }}" alt="Foto Perfil" width="50" height="60">
                    </div>
			    @else
                    <div class="float-left">
			             <img src="{{ asset('storage/profiles/avatar.jpg') }}" alt="Foto Perfil" width="50" height="60">
                    </div>
			    @endif
        	</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->phone }}</td>
            <td> 
            <form action="{{ route('users.associate.destroy', 200) }}" method="post" role="form" class="btn-block">
                    @csrf
                    @method('delete')
                    <input type="hidden" name="id" value="{{ 200 }} ?>"> 
                <button type="submit" class="btn btn-xs btn-secondary btn-block">Remove</button>
            </form>
            </td>
        </tr>
    @endforeach
    </table>

@else 
    <h2>No users found</h2> 

@endif
@endsection('content')