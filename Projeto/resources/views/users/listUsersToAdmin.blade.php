@extends('master')
@section('content')

@if (count($users))
    <table class="table table-striped">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Admin</th>
            <th>Blocked</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($users as $user)    
        <tr>
            <td>{{ $user->name}}</td>
            <td>{{ $user->email}}</td>
            <td> @if($user->admin == 1)
                    Sim 
                 @else 
                    Não
                 @endif 
              </td>
            <td> @if($user->blocked == 1)
                    Sim 
                 @else 
                    Não
                 @endif 
              </td>
        </tr>
        @endforeach
    </table>
    

@else 
    <h2>No users found</h2>

@endif
@endsection('content')