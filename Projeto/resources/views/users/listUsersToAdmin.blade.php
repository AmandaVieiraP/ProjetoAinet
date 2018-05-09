@extends('master')
@section('content')

@if(Session::has('errorMsg'))
    @alert(['type' => 'danger','title'=>'Warning!'])
        {{session('errorMsg')}}
    @endalert

    @elseif(Session::has('successMsg'))
        @alert(['type' => 'success','title'=>'Success!'])
            {{session('successMsg')}}
        @endalert
@endif


<div class="container-fluid bg-secondary text-light">
    <h4>Filter User</h4>
    <form action="{{ route('list.of.all.users') }}" method="get" class="form-inline">
        <div class="form-group">
            <label for="inputName" class="mr-sm-2">Name</label>
            <input type="text" name="name" id="inputName" placeholder=" Enter name" class="form-control mb-2 mr-sm-2 mb-sm-0" value="{{ old('name') }}">
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
            <th>Actions</th>
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
              <td>
                @if($user == Auth::user())

                @else 
                    <div class='col-xs-3'>
                    @if($user->blocked == 1)
                        
                        <form action="{{ action('UserController@unblockUser', $user->id) }}" method="POST" role="form" class="btn-block">
                        @csrf
                        @method('patch')
                        <input type="hidden" name="user_id" value="{{ $user->id }} ?>">
                        <button type="submit" class="btn btn-xs btn-secondary btn-block">Unblock</button>
                        </form>
                     @else 
                        <form action="{{ action('UserController@blockUser', $user->id) }}" method="POST" role="form" class="btn-block">
                        @csrf
                        @method('patch')
                        <input type="hidden" name="user_id" value="{{ $user->id }} ?>">
                        <button type="submit" class="btn btn-xs btn-secondary btn-block">Block</button>
                        </form> 
                     @endif   
                     {{-- parte do demote --}}
                     @if($user->admin == 1)
                        <form action="{{ action('UserController@demoteUser', $user->id) }}" method="POST" role="form" class="btn-block">
                        @csrf
                        @method('patch')
                        <input type="hidden" name="user_id" value="{{ $user->id }} ?>">
                        <button type="submit" class="btn btn-xs btn-secondary btn-block">Demote</button>
                        </form>
                     @else 
                        <form action="{{ action('UserController@promoteUser', $user->id) }}" method="POST" role="form" class="btn-block">
                        @csrf
                        @method('patch')
                        <input type="hidden" name="user_id" value="{{ $user->id }} ?>">
                        <button type="submit" class="btn btn-xs btn-secondary btn-block">Promote</button>
                        </form>
                        

                     @endif 
                     </div>
                @endif
              </td>
        </tr>
        @endforeach
    </table>
    

@else 
    <h2>No users found</h2>

@endif
@endsection('content')