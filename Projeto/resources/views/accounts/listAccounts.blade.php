@extends('master')
@section('content')

@if(Session::has('warningMsg'))
    @alert(['type' => 'warning','title'=>'Warning!'])
        {{session('warningMsg')}}
    @endalert

    @elseif(Session::has('successMsg'))
        @alert(['type' => 'success','title'=>'Success!'])
            {{session('successMsg')}}
        @endalert
@endif

<div id='espaco'>
    <h4> <b>User: </b> {{ $user->name }} </h4>
</div>

<nav class="navbar navbar-expand-lg navbar-light bg-light
">

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
      <li>
        <a class="nav-link" href="{{ route('accounts',['user'=>$user->id]) }}">All Accounts</a>
      </li>
      <li>
        <a class="nav-link" href="{{ route('accounts.closed',['user'=>$user->id]) }}">Only Closed Accounts</a>
      </li>
      <li>
        <a class="nav-link" href="{{ route('accounts.opened',['user'=>$user->id]) }}">Open Accounts</a>
      </li>
      <li>
        <a class="nav-link" href="{{ route('account.create') }}">Add New Account</a>
      </li>
    </ul>
  </div>
</nav>

<br>

@if (count($accounts))
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Code</th>
                <th>Type</th>
                <th>Date</th>
                <th>Current balance</th>
                <th>Status</th>
                <th>Actions</th>
                <th>Movements List</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($accounts as $account)    
            <tr>
                <td>{{ $account->code}}</td>
                <td> 
                    @foreach($accounts_type as $type) 
                        @if ($account->account_type_id == $type->id)
                            {{ $type->name }}
                        @endif
                    @endforeach
                </td>
                <td>{{ $account->date }} </td>
                <td>{{ $account->current_balance}} </td> 
                <td> 
                     @if (is_null($account->deleted_at)) 
                        <span> Open </span>
                     @else 
                        <span> Closed </span>
                     @endif
                </td>
                <td> 
                  
                    @if(is_null($account->deleted_at))
                    <form action="{{ route('account.close',['account'=>$account->id]) }}" method="POST" role="form" class="btn-block">
                        @csrf
                        @method('patch')
                        <button type="submit" class="btn btn-xs btn-secondary btn-block">Close</button>
                    </form>
                    @else
                    <form action="{{ route('account.reopen',['account'=>$account->id]) }}" method="POST" role="form" class="btn-block">
                        @csrf
                        @method('patch')
                        <button type="submit" class="btn btn-xs btn-secondary btn-block">Reopen</button>
                    </form>
                    @endif
                    <form action="{{ route('account.delete',['account'=>$account->id]) }}" method="POST" role="form" class="btn-block">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-xs btn-secondary btn-block">Delete</button>
                    </form> 
                    <a class="btn btn-xs btn-secondary btn-block" href="{{ route('account.edit',['account'=>$account->id]) }}">Edit</a>   
                </td>
                <td class="align-middle">
                    <a class="btn btn-xs btn-secondary btn-block" href="{{ route('movements.index',['account'=>$account->id]) }}">Access</a> 
                </td>
            </tr>
        @endforeach
    </table>
@else 
   <h5>No accounts found</h5>
@endif

@endsection('content')