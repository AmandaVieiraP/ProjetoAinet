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

@if(Session::has('successMsg'))
    @alert(['type' => 'success','title'=>'Success!'])
        {{session('successMsg')}}
    @endalert
@endif

@include('users.partials.menuProfile')

<p>
<a href="{{ route('get.createAssociate') }}" class="btn btn-secondary">Add New Associate</a>
</p>

@if (count($users))
<table class="table table-striped">
    <thead>
        <tr>
            <th></th>
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
            <td class="text-center"><strong>{{ $loop->index+1 }}</strong></td>
            <td>
                @if (auth()->user()->profile_photo)
                    <div class="float-left">
                         <img src="{{ asset('storage/profiles/'.$user->profile_photo) }}" alt="Foto Perfil" width="50" height="60" class="img-round">
                    </div>
                @else
                    <div class="float_left">
                        <img src="{{ asset('images/avatar.png') }}" width="50" height="60" alt="Foto Perfil" class="img-round">
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
    <div class="pagination pagination-centered">
        {{ $users->links() }}
    </div>

@else 
    <h2>No users found</h2> 

@endif
@endsection('content')