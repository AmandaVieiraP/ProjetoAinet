@extends('master')
@section('content')

@include('accounts.partials.mainHeader')

@if (count($accounts))
    <table class="table table-striped">
        <thead>
            <tr>
            	<th>Account ID</th>
            	<th>Summary (€)</th>
                <th>Percentage (%)</th>
            </tr>
        </thead>
        <tbody>
        @for($i = 0; $i < count($accounts); $i++)
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
            	{{ number_format($total, 2) }}
            </td>
            <td></td>
        </tr>
    	</tbody>
    </table>
    
@else 
    <h2>No accounts found</h2>
@endif

@endsection('content')