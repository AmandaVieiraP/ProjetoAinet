@extends('master')
@section('content')

@if ($errors->any())
    @alert(['type' => 'danger','title'=>'Errors'])
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endalert
@endif

@if(Session::has('errorMsg'))
    @alert(['type' => 'danger','title'=>'Warning!'])
        {{session('errorMsg')}}
    @endalert

    @elseif(Session::has('successMsg'))
        @alert(['type' => 'success','title'=>'Success!'])
            {{session('successMsg')}}
        @endalert
@endif

<form method="POST" action="{{ route('movement.update', $movement->id) }}" enctype="multipart/form-data">
    @csrf
    @method('put')
    @include('movements.partials.addEditMovement')

    <div class="form-group">
        <div class="col-sm-offset-5 col-sm-6">
            <button type="submit" class="btn btn-outline-primary" name="ok">Accept</button>
            <button type="submit" class="btn btn-outline-primary" name="cancel">Cancel</button>
        </div>
    </div>
</form>


@endsection('content')