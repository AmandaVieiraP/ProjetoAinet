
    <div div class="form-group">
    	<label for="accountType" class="col-sm-4 col-form-label"> Account Type</label>
    	<div class="col-sm-10">
            <select class="custom-select mb-2 mr-sm-2 mb-sm-0" id="accountType" name="account_type_id">
                <option disabled selected>Choose a Option</option>
                @foreach ($types as $type) 
                <option {{old('account_type_id',$type->name)}} value="{{$type->id}}">{{$type->name}}</option>
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
