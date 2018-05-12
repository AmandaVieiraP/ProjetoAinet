@extends('master')
@section('content')

<div class='float-left' id='espaco'>
	<h4> <b>User: </b> {{ $user->name }} </h4>
</div>
<div class='float-right' id='espaco'> 
	<a class="btn btn-xs btn-secondary" href="{{ action('AccountController@showAccounts', $user->id) }}">All Accounts</a>
	<a class="btn btn-xs btn-secondary" href="{{ action('AccountController@showOpenAccounts', $user->id) }}">Only Open Accounts</a>
	<a class="btn btn-xs btn-secondary" href="{{ action('AccountController@showCloseAccounts', $user->id) }}">Only Closed Accounts</a>
</div>

@if (count($accounts))
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Code</th>
                <th>Type</th>
                <th>Date</th>
                <th>Current balance</th>
                <th>Status</th>
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
            </tr>
        @endforeach
    </table>
@else 
   <h5>No accounts found</h5>

@endif



@endsection('content')