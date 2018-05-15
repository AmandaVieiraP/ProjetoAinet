@extends('master')
@section('content')

@if(count($movements))
	<table class="table table-striped">
        <thead>
            <tr>
                <th>Category</th>
                <th>Date</th>
                <th>Value</th>
                <th>Type</th>
                <th>End Balance</th>
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
            </tr>
        @endforeach
    </table>


@else
  <h2>No movements founds for that account </h2>
@endif

@endsection('content')