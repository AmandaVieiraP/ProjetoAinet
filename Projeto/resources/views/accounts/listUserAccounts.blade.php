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

@include('accounts.partials.mainHeader')

@if (count($accounts))
    <table class="table table-striped">
        <thead>
            <tr>
                <th></th>
                <th>Code</th>
                <th>Type</th>
                <th>Date</th>
                <th>Current balance</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($accounts as $account)    
            <tr>
                <td class="text-center"><strong>{{ $loop->index+1 }}</strong></td>
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
                    <form action="{{ route('account.close', $account->id) }}" method="POST" role="form" class="btn-block">
                        @csrf
                        @method('patch')
                        <input type="hidden" name="id" value="{{ $account->id }}">
                        <button type="submit" class="btn btn-xs btn-secondary btn-block">Close</button>
                    </form>
                    @else
                    <form action="{{ route('account.reopen', $account->id) }}" method="POST" role="form" class="btn-block">
                        @csrf
                        @method('patch')
                        <input type="hidden" name="id" value="{{ $account->id }}">
                        <button type="submit" class="btn btn-xs btn-secondary btn-block">Reopen</button>
                    </form>
                    @endif

                    <form action="{{ route('account.delete', $account->id) }}" method="POST" role="form" class="btn-block">
                        @csrf
                        @method('delete')
                        <input type="hidden" name="id" value="{{ $account->id }}">
                        <button type="submit" class="btn btn-xs btn-secondary btn-block">Delete</button>
                    </form>
                    <a class="btn btn-xs btn-secondary btn-block" href="{{ route('movement.index', $account->id) }}">Movements</a>   
                </td>
            </tr>
        @endforeach
    </table>
    <div class="pagination pagination-centered">
        {{ $accounts->links() }}
    </div> 
@else 
   <h5>No accounts found</h5>

@endif
@endsection('content')