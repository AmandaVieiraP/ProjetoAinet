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

<form method="POST" action="{{ route('accounts.updateAccount', $account->id) }}">
    @method('put')
    @csrf

    <div div class="form-group">
    	<label for="code" class="col-sm-4 col-form-label"> Code</label>
    	<div class="col-sm-10">
      		<input type="text" name="code" class="form-control" id="code" value="{{$account->code}}">
    	</div>
    </div>

    <div div class="form-group">
    	<label for="balance" class="col-sm-4 col-form-label"> Start Balance</label>
    	<div class="col-sm-10">
      		<input type="text" name="start_balance" class="form-control" id="balance" value="{{$account-> start_balance}}">
    	</div>
    </div>

     <div div class="form-group">
    	<label for="description" class="col-sm-4 col-form-label">Description</label>
    	<div class="col-sm-10">
      		<input type="text" name="description" class="form-control" id="description" value="{{$account-> description}}">
    	</div>
    </div>

     <div div class="form-group">
    	<label for="description" class="col-sm-4 col-form-label">Type</label>
    	<div class="col-sm-10">
      		 <select name="account_type_id">
  				@foreach ($types->all() as $tipo)
  					<option value="{{$tipo->id}}">{{$tipo->name}}</option>
      			@endforeach
  			 </select> 	
    	</div>
    </div>
    <div class="form-group">
    	<label for="accountDate" class="col-sm-4 col-form-label"> Date</label>

    	<div class="col-sm-10">
      		<input type="date" name="date" class="form-control" id="accountDate" placeholder="Date" value="{{ old('date') }}">
    	</div>
    </div>

    <div class="form-group">
    	<div class="col-sm-offset-5 col-sm-6">
    		<button type="submit" class="btn btn-outline-primary" name="ok">Submit</button>
    		<button type="submit" class="btn btn-outline-primary" name="cancel">Cancel</button>
    	</div>
    </div>

</form>
@endsection