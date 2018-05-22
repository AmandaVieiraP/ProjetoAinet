@extends('master')
@section('content')

@if (Session::has('successMsg'))
    @alert(['type' => 'success','title'=>'Success!'])
        {{ session('successMsg') }}
    @endalert
@endif

@if ($errors->any())
    @alert(['type' => 'danger','title'=>'Errors'])
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endalert
@endif

@if(count($movements))
    <table class="table table-striped">
        <thead>
            <tr class="text-center">
                <th>Category</th>
                <th>Date</th>
                <th>Value</th>
                <th>Type</th>
                <th>End Balance</th>
                <th colspan="3">Document</th>
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
                    <a href="{{ route('documents.create',['movement' => $movement->id]) }}" class="btn btn-secondary btn-xs">Upload</a>
                </td>
                @if (!is_null($movement->document_id))
                <td>
                    <a href="{{ route('document.show',['document'=>$movement->document_id])}}"  class="btn btn-secondary btn-xs">Download</a>
                </td>
                <td>
                    <form action="{{ route('document.destroy',['document'=>$movement->document_id]) }}" method="POST">
                        @method('delete')
                        @csrf
                        <button type="submit" class="btn btn-secondary btn-xs">Delete</button>
                    </form>
                </td>
                @else
                <td></td>
                <td></td>
                @endif
            </tr>
        @endforeach
    </table>

@else
  <h2>No movements founds for that account </h2>
@endif

@endsection('content')