@extends('layouts.default')
@section('content')
<div id= "main" class="col-md-offset-1 col-md-10">
  <nav>
    <ul/>
    <ul class="nav navbar-nav">
        <li><a href="{{ route('stickwcup.show') }}"  class="btn btn-info btn-lg"><span class="glyphicon glyphicon-cloud-upload"/>Stick with Cup</a></li>
        <li><a href="{{ route('logout') }}" class="btn btn-info btn-lg"><span class="glyphicon glyphicon-cloud-upload"/>Vial</a></li>

        <li><a href="{{ route('logout') }}" class="btn btn-info btn-lg"><span class="glyphicon glyphicon-cloud-upload"/>Compact&Palette</a></li>
        <li><a href="{{ route('users.show', [Auth::user()]) }}" class="btn btn-info btn-lg"><span class="glyphicon glyphicon-user"/>Users</a></li>
    </ul>
  </nav>
</div>
@stop