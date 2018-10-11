<!DOCTYPE html>
<html>
  <head>
    <title>Sourcing App</title>
    <link rel="stylesheet" href="/css/app.css">
  </head>
  <body>
    <header class="navbar navbar-fixed-top navbar-inverse">
      <div class="container">
        <div class="col-md-offset-1 col-md-10">
          <a href="/" id="logo">Sourcing</a>
          <nav>
            <ul class="nav navbar-nav navbar-right">
              <li><a href="/help">Help</a></li>
              <li><a href="/about">About</a></li>
            </ul>
          </nav>
        </div>
      </div>
    </header>

    <div class="container">
      <div class="col-md-offset-1 col-md-10">
        @include('shared._messages')
        @yield('content')
      </div>
    </div>
  </body>
</html>