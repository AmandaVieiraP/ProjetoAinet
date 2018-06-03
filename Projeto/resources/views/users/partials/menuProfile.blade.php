<nav class="navbar navbar-expand-lg navbar-light bg-light
    ">

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li>
        <a class="nav-link" href="{{ route('me.profileForm') }}"><strong>Update Profile</strong></a>
        </li>
        <li>
          <a class="nav-link" href="{{ route('me.passwordForm') }}"><strong>Change Password</strong></a>
        </li>
        <li>
          <a class="nav-link" href="{{ route('users.profiles') }}"><strong>View Other Profiles</strong></a>
        </li>
        <li>
          <a class="nav-link" href="{{ route('users.associates') }}"><strong>View My Associates</strong></a>
        </li>
        <li>
          <a class="nav-link" href="{{ route('me.associateOf') }}"><strong>View Associates-Of</strong></a>
        </li>
      </ul>
    </div>
</nav>

<br>