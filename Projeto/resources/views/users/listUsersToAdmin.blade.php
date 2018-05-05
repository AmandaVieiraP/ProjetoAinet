@extends('master')
@section('content')
<div class="container-fluid bg-secondary text-light">
    <h4>Filter User</h4>
    <form action="{{ route('list.of.all.users') }}" method="get" class="form-inline">
        <div class="form-group">
            <label for="inputName" class="mr-sm-2">Name</label>
            <input type="text" name="name" id="inputName" placeholder=" Enter name" class="form-control mb-2 mr-sm-2 mb-sm-0">
        </div>
        <div class="form-group">
            <label for="selectType" class="mr-sm-2">Type</label>
            <select name="type" id="selectType" class="custom-select mb-2 mr-sm-2 mb-sm-0">
                <option disabled selected> Choose Type </option>
                <option value="normal">Normal</option>
                <option value="admin">Administrator</option>
            </select>
        </div>
        <div class="form-group">
            <label class="mr-sm-2" for="selectStatus">Status</label>
            <select name="status" id="selectStatus" class="custom-select mb-2 mr-sm-2 mb-sm-0">
                <option disabled selected>Choose Status</option>
                <option value="blocked">Blocked</option>
                <option value="unblocked">Not Blocked</option>
            </select>
        </div>
        <button type="submit" class="btn btn-light">Apply Filter</button>
    </form>
</div>
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