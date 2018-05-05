@extends('master') 
@section('content') 

@if(Session::has('msg'))
@alert(['type' => 'danger','title'=>'Warning!'])
Unauthorized Access!
@endalert
@endif

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

