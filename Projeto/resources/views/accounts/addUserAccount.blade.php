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
<form method="POST" action="{{ route('accounts.store') }}">
    @csrf
    <div div class="form-group">
        <label for="accountType" class="col-sm-4 col-form-label"> Account Type</label>
        <div class="col-sm-10">
            <select class="custom-select mb-2 mr-sm-2 mb-sm-0" id="accountType" name="account_type_id">
                <option disabled selected>Choose a Option</option>
                @foreach ($types as $type) 
                <option {{is_selected(old('account_type_id',$type->name),$type->id)}} value="{{$type->id}}">{{$type->name}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group">
        <label for="accountCode" class="col-sm-4 col-form-label"> Code</label>
        <div class="col-sm-10">
            <input type="text" name="code" class="form-control" id="accountCode" placeholder="Code" value="{{ md5(microtime())}}">
        </div>
    </div>

    <div class="form-group">
        <label for="accountDate" class="col-sm-4 col-form-label"> Date</label>

        <div class="col-sm-10">
            <input type="date" name="date" class="form-control" id="accountDate" placeholder="Date" value="{{ old('date') }}">
        </div>
    </div>

    <div class="form-group">
        <label for="accountStartBalance" class="col-sm-4 col-form-label"> Start Balance</label>
        <div class="col-sm-10">
            <input type="text" name="start_balance" class="form-control" id="accountStartBalance" placeholder="0.00" value="{{ old('start_balance') }}">
        </div>
    </div>
    <div class="form-group">
        <label for="accountDescription" class="col-sm-4 col-form-label"> Description</label>
        <div class="col-sm-10">
            <textarea name="description" class="form-control" id="accountDescription" placeholder="Description" value="{{ old('description') }}" row="2">
            </textarea>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-5 col-sm-6">
            <button type="submit" class="btn btn-outline-primary" name="ok">Add Account</button>
            <button type="submit" class="btn btn-outline-primary" name="cancel">Cancel</button>
        </div>
    </div>
@endsection