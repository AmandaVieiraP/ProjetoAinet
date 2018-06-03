@extends('master')
@section('content')

@include('accounts.partials.mainHeader')

@if (count($accounts))

<div class="container-fluid">
  <div class="row">
    <div class="col-sm-6">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Account ID</th>
                <th>Summary (€)</th>
                <th>Percentage (%)</th>
            </tr>
        </thead>
        <tbody>
        @for($i = 0; $i < count($summary); $i++)
        <tr>
            <td>
                {{ $accounts[$i]->id }}
            </td>
            <td>
                {{ $summary[$i] }}
            </td>
            <td>
                {{ $percentage[$i] }}
            </td>
        </tr>
        @endfor
        <tr>
            <td>
                <strong>Grand Total</strong>
            </td>
            <td>
                {{ $total }}
            </td>
            <td></td>
        </tr>
        </tbody>
    </table>
    </div>
    <div class="col-sm-6">
      @include('accounts.partials.accountPercentage', ['summary' => $summary, 'accounts' => $accounts])
    </div>
  </div>

</div>
<br>
@if ($errors->any())
    @alert(['type' => 'danger','title'=>'Errors'])
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endalert
@endif
<div class="container-fluid" id='bg-light-grey'>
    <h5> Total revenues and expenses by category between: </h5>
    <form method="GET" action="{{ route('user.totalExpensesRevenues',['user'=>$id])}}" class="form-inline">
        <div class="form-group">
            <label for="initalDate" class="mr-sm-2"> Initial Date</label>
            <input type="date" name="initial" class="form-control mb-2 mr-sm-2 mb-sm-0" id="initialDate" placeholder="Date" value="{{ old('initial') }}">
        </div>
        <div class="form-group">
            <label for="finalDate" class="mr-sm-2"> Final Date</label>
            <input type="date" name="final" class="form-control mb-2 mr-sm-2 mb-sm-0" id="finalDate" placeholder="Date" value="{{ old('final') }}">
        </div>
        <button type="submit" class="btn btn-secondary">View Results</button>
    </form>
    </br>
</div>

<div class="container-fluid" id='bg-light-grey'>
    <h5> Evolution of expenses and revenues by category between: </h5>
    <form method="GET" action="{{ route('user.evolutionExpensesRevenues',['user'=>$id])}}" class="form-inline">
        <div class="form-group">
            <label for="initalDate" class="mr-sm-2"> Initial Date</label>
            <input type="date" name="initial" class="form-control mb-2 mr-sm-2 mb-sm-0" id="initialDate" placeholder="Date" value="{{ old('initial') }}">
        </div>
        <div class="form-group">
            <label for="finalDate" class="mr-sm-2"> Final Date</label>
            <input type="date" name="final" class="form-control mb-2 mr-sm-2 mb-sm-0" id="finalDate" placeholder="Date" value="{{ old('final') }}">
        </div>
        <button type="submit" class="btn btn-secondary">View Results</button>
    </form>
    </br>
</div>
   
@else 
    <h2>No accounts found</h2>
@endif

@endsection('content')