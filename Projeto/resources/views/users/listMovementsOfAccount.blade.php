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

<h5> <b> Account code: </b> {{ $account->code }} </h5>
</br>

@if (is_null($account->deleted_at))
<div class='float-left' id='espaco'> 
    <a class="btn btn-xs btn-secondary" href="{{ route('movement.create', $account->id) }}">Add Movement</a>
</div>
@endif

@if(count($movements))
	<table class="table table-striped">
        <thead>
            <tr>
                <th>Category</th>
                <th>Date</th>
                <th>Value</th>
                <th>Type</th>
                <th>End Balance</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($movements as $movement)    
            <tr>
                <td>
                	@foreach($movementCategories as $category)
                		@if ($movement->movement_category_id == $category->id)
                			{{ $category->name }}
                		@endif
                	@endforeach
                </td>
                <td>{{ $movement->date }}</td>
                <td> {{ $movement->value }} </td>
                <td> {{ $movement->type }} </td> 
                <td> {{ $movement->end_balance }} </td>
                <td> 
                    <a class="btn btn-xs btn-secondary  btn-block" href="{{ route('movement.edit', $movement->id)  }}">Edit</a>
                    <form action="{{ route('movement.destroy', $movement->id) }}" method="POST" role="form" class="btn-block">
                        @csrf
                        @method('delete')
                        <input type="hidden" name="movementId" value="{{ $movement->id }}">
                        <button type="submit" class="btn btn-xs btn-secondary btn-block">Delete</button>
                    </form> 
                </td>
            </tr>
        @endforeach
    </table>


@else
    </br>
    <h2>No movements founds for that account </h2>
@endif

@endsection('content')