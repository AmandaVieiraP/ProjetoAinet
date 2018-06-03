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

@if (count($users))

<form method="POST" action="{{ route('me.createAssociate')}}">
@csrf

  <h6>Pick on user from list and click on 
    <button type="submit" class="btn btn-outline-primary" name="ok">Associate User</button>
    or
    <button type="submit" class="btn btn-outline-primary" name="cancel">Cancel</button>
  </h6>
  <table class="table">
    <tbody>
    	@for($i = 0; $i < count($users); $i++)
        @if($users[$i]->id != Auth::user()->id && !in_array($users[$i]->id, $associatedsIds))
          <tr>
            <th scope="row">{{ $i+1 }}</th>
            <td class="text-center">
              @if ($users[$i]->profile_photo)
                <img src="{{ asset('storage/profiles/'.$users[$i]->profile_photo) }}" alt="Foto Perfil" class="img-round">
              @else
                <img src="{{ asset('storage/profiles/avatar.jpg') }}" alt="Foto Perfil" class="img-round">
              @endif
            </td class="text-center">
            <td>{{ $users[$i]->name }}</td>
            <td class="text-center">
                <div class="form-check">
                  <input class="form-check-input" type="radio" value="{{ $users[$i]->id }}" name="associated_user">
                </div>
            </td>
          </tr>
        @endif
      @endfor

    </tbody>
  </table>

</form>
@else 
    <h2>No users found</h2> 

@endif
	
@endsection