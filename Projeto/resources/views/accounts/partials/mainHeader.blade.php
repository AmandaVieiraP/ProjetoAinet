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
        <a class="nav-link" href="{{ route('dashboard',['user'=>$user->id]) }}">Dashboard</a>
      </li>
      <li>
        <a class="nav-link" href="{{ route('accounts',['user'=>$user->id]) }}">All Accounts</a>
      </li>
      <li>
        <a class="nav-link" href="{{ route('accounts.closed',['user'=>$user->id]) }}">Only Closed Accounts</a>
      </li>
      <li>
        <a class="nav-link" href="{{ route('accounts.opened',['user'=>$user->id]) }}">Open Accounts</a>
      </li>
      <li>
        <a class="nav-link" href="{{ route('account.create') }}">Add New Account</a>
      </li>
    </ul>
  </div>
</nav>

<br>