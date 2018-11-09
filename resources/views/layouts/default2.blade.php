<!DOCTYPE html>
<html>
  <head>
    <title>Sourcing App</title>
    <link rel="stylesheet" href="/css/app.css">
  </head>
  <body>
    @include('layouts._header')
    @include('shared._messages')
    @yield('content')
  </body>
</html>