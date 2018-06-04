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
<nav class="navbar navbar-expand-lg navbar-light bg-light
    ">

      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li>
            <a class="nav-link" href="{{ route('my.accounts') }}"><strong>Go Back</strong></a>
          </li>
        </ul>
        <ul class="navbar-nav">
          <li>
            <a class="nav-link" href="{{ route('movement.create', $account->id) }}"><strong>Add Movement</strong></a>
          </li>
        </ul>
      </div>
    </nav>
    <br>
@endif

@if(count($movements))
    <table class="table table-striped">
        <thead>
            <tr>
                <th></th>
                <th>Category</th>
                <th>Date</th>
                <th>Value</th>
                <th>Type</th>
                <th>End Balance</th>
                <th class="text-center">Actions</th>
                <th colspan="4" class="text-center">Document</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($movements as $movement)    
            <tr>
                <td class="text-center"><strong>{{ $loop->index+1 }}</strong></td>
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
                        <button type="submit" class="btn btn-xs btn-secondary  btn-block">Delete</button>
                    </form> 
                </td>
                <td>
                    <a href="{{ route('documents.create',['movement' => $movement->id]) }}" class="btn btn-secondary btn-xs">Upload</a>
                </td>
                @if (!is_null($movement->document_id))
                <td>
                    <a href="{{ route('document.show',['document'=>$movement->document_id])}}"  class="btn btn-secondary btn-xs">Download</a>
                </td>
                <td>
                    <a href="{{ route('document.show',['document'=>$movement->document_id])}}"  class="btn btn-secondary btn-xs" name="view">View</a>
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
    <div class="pagination pagination-centered">
        {{ $movements->links() }}
    </div> 
@else
    </br>
    <h2>No movements founds for that account </h2>
@endif

@endsection('content')