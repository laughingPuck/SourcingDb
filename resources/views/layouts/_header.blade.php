<header class="navbar navbar-fixed-top navbar-inverse">
  <div class="container">
    <div class="col-md-offset-1 col-md-10">
      <a href="/" id="logo">Sourcing DB</a>
      <nav>
        <ul class="nav navbar-nav navbar-right">
          @if (Auth::check())
            <li>
              <a id="logout" href="#">
                <form action="{{ route('logout') }}" method="POST">
                  {{ csrf_field() }}
                  {{ method_field('DELETE') }}
                  <button class="btn btn-block btn-danger" type="submit" name="button">Log Out</button>
                </form>
              </a>
            </li>
          @else
            <li><a href="{{ route('login') }}">Help</a></li>
            <li><a href="{{ route('login') }}">Login</a></li>
          @endif
        </ul>
      </nav>
    </div>
  </div>
</header>