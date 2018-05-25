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

<div class="card">
    <div class="card-header text-center">
        <h5>Movement</h5>
    </div>

    <div class="card-body">
    <span class="card-text"> <strong>ID: </strong> {{$movement->id}} </span>
    <br>

    <span class="card-text"> <strong>Account Owner: </strong> {{Auth::user()->name}} </span>
    <br>

    <span class="card-text"> <strong>Account Number: </strong> {{$movement->account_id}} </span>
    <br>
        
    <span class="card-text"> <strong>Type: </strong> {{$movement->type}} </span>
    <br>
        
    <span class="card-text"><strong>Document ID: </strong> {{$movement->document_id}} </span>
    <br>

    <span class="card-text"><strong>Document Description: </strong> {{ old('document_description') }} </span>
    
    </div>

</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('documents.update',['movement' => $movement->id]) }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group row">
                <label for="docDescription" class="col-sm-2 col-form-label">Description</label>
                <div class="col-sm-10">
                <textarea name="document_description" class="form-control" id="docDescription" placeholder="Description" value="{{ old('document_description') }}" row="2"></textarea>
                </div>
            </div>

            <div class="form-group row">
                <label for="docFile" class="col-sm-2 col-form-label">Upload Document</label>
                <div class="btn-group" role="group">
                    <input type="file" name="document_file" id="docFile" class="form-control" accept=".pdf,.jpeg,.png">
                    <button class="btn btn-outline-primary" name="ok">Ok</button>
                    <button class="btn btn-outline-primary" name="cancel">Cancel</button>
                </div>
                
            </div>
        </form>
    </div>
</div>
@endsection