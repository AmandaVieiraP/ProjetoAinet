@extends('master') 
@section('content') 

{{ @session('msg') }}

<table class="table table-striped">
	<thread> 
		<tbody>
			<tr> 
				<td> Total of Users </td>
				<td> {{ count($users) }} </td>
			</tr>
			<tr>
				<td> Total of Accounts </td>
				<td> {{ count($accounts) }} </td>
			</tr>
			<tr>
				<td> Total of Movements </td>
				<td> {{ count($movements) }} </td>
			</tr>
		</tbody>
	</thread>
</table>

@endsection

