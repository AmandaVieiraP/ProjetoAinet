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
<form method="POST" action="{{ route('account.store') }}">
    @csrf
    
    @include('accounts.partials.create-edit')

    <div class="form-group">
        <div class="col-sm-offset-5 col-sm-6">
            <button type="submit" class="btn btn-outline-primary" name="ok">Add Account</button>
            <button type="submit" class="btn btn-outline-primary" name="cancel">Cancel</button>
        </div>
    </div>
@endsection