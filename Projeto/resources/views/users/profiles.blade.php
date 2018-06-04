@extends('master')
@section('content')

@include('users.partials.menuProfile')

<div class="container-fluid" id='bg-light-grey'>
    <h5> Search user by name </h5>
    <form action="{{ route('users.profiles') }}" method="get" class="form-inline">
        <div class="form-group">
            <label for="inputName" class="mr-sm-2">Name</label>
            <input type="text" name="name" id="inputName" placeholder=" Enter name" class="form-control mb-2 mr-sm-2 mb-sm-0" value="{{ old('name') }}">
        </div>
        <button type="submit" class="btn btn-secondary">Apply Filter</button>
    </form>
    </br>
</div>

@if (count($users))
    <table class="table table-striped">
        <thead>
            <tr>
                <th> </th>
                <th> </th>
                <th>Name</th>
                <th>Associate</th>
                <th>Associate-of</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($users as $user)    
            <tr>
                <td class="text-center"><strong>{{ $loop->index+1 }}</strong></td>
                <td>
                    @if (is_null($user->profile_photo))
                        <div class="float-left"> 
                            <img src="{{asset('storage/profiles/avatar.jpg')}}" alt="profile_photo" width="50" height="60" class="img-round">
                        </div>
                    @else 
                        <div class="float-left"> 
                            <img src="{{asset('storage/profiles/'.$user->profile_photo)}}" alt="profile_photo" width="50" height="60" class="img-round">
                        </div>
                    @endif
                </td>
                <td>{{ $user->name}}</td>
                <td> 
                    @foreach($associateds as $associated) 
                        @if ($user->id == $associated->id)
                            <div class="float-left"> 
                                <img src="/images/check_box_1.png" alt="user" width="20" height="20">
                                <span>associate</span>
                            </div>
                        @endif
                    @endforeach
                </td>
                <td>  
                    @foreach($associated_of as $associated_of_users) 
                        @if ($user->id == $associated_of_users->id)
                            <div class="float-left"> 
                                <img src="/images/check_box_1.png" alt="user" width="20" height="20">
                                <span>associate-of</span>
                            </div> 
                        @endif
                    @endforeach                
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