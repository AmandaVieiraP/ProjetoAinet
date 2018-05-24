    <div class="form-row">
	    <div class="form-group col-sm-4">
	    	<label for="movement_category_id"> Movement Category</label>
	    		<select class="form-control" name="movement_category_id">
	   	 			@foreach($movementsCategories as $movementsCategory)
                        @if (!is_null($movement) && $movementsCategory->id == $movement->movement_category_id) 
                            <option value="{{ $movementsCategory->id }}" selected>
                                {{ $movementsCategory->name }}
                            </option>
                        @else
                            <option value="{{ $movementsCategory->id }}">
                                {{ $movementsCategory->name }}
                            </option>
                        @endif
	    			@endforeach
				</select>
		</div>

		<div class="form-group col-sm-4">
			<label for="date">Date</label>	
        	<input type="date" name="date" class="form-control" id="date" placeholder="Date" value="{{ old('date', $movement->date) }}">
		</div>
		<div class="form-group col-sm-4">
    		<label for="value">Value</label>
      		<input type="text" name="value" class="form-control" id="value" placeholder="Value" value="{{ old('value', $movement->value) }} ">
    	</div>
	</div>
   
    <div div class="form-group">
    	<label for="movementDescription">Movement Description (optional)</label>
      	<input type="text" name="description" class="form-control" id="movementDescription" placeholder="Description" value="{{ old('description', $movement->description) }}">
    </div>

    <div class="form-group">
        <label for="document_file">Upload Document (optional)</label>
        <input type="file" name="document_file" id="document_file" class="form-control" accept=".jpeg,.png,.pdf">
    </div>
    <div div class="form-group">
    	<label for="document_description">Document Description (optional) </label>
      	<input type="text" name="document_description" class="form-control" id="document_description" placeholder="Document Description" value=" {{ old('document_description', $document->description) }} ">
    </div>

