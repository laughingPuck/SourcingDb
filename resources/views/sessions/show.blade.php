@extends('layouts.default')
@section('content')
<div class="col-md-offset-1 col-md-10">
  <nav>
    <ul/>
    <ul class="nav navbar-nav">
        <li><a href="{{ route('logout') }}"  class="btn btn-info btn-lg"><span class="glyphicon glyphicon-cloud-upload"/>ProductA</a></li>
        <li><a href="{{ route('logout') }}" class="btn btn-info btn-lg"><span class="glyphicon glyphicon-cloud-upload"/>ProductB</a></li>

        <li><a href="{{ route('logout') }}" class="btn btn-info btn-lg"><span class="glyphicon glyphicon-cloud-upload"/>ProductC</a></li>

        <li><a href="{{ route('users.show', [Auth::user()]) }}" class="btn btn-info btn-lg"><span class="glyphicon glyphicon-user"/>Users</a></li>
    </ul>
  </nav>
</div>
@stop