<div id='espaco'>
    <h4> <b>User: </b> {{ $user->name }} </h4>
</div>

<nav class="navbar navbar-expand-lg navbar-light bg-light
">

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
      <li>
        <a class="nav-link" href="{{ route('dashboard',['user'=>$user->id]) }}"><strong>Dashboard</strong></a>
      </li>
      <li>
        <a class="nav-link" href="{{ route('accounts',['user'=>$user->id]) }}"><strong>All Accounts</strong></a>
      </li>
      <li>
        <a class="nav-link" href="{{ route('accounts.closed',['user'=>$user->id]) }}"><strong>Only Closed Accounts</strong></a>
      </li>
      <li>
        <a class="nav-link" href="{{ route('accounts.opened',['user'=>$user->id]) }}"><strong>Open Accounts</strong></a>
      </li>
      <li>
        <a class="nav-link" href="{{ route('account.create') }}"><strong>Add New Account</strong></a>
      </li>
    </ul>
  </div>
</nav>

<br>