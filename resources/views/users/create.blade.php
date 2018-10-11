@extends('layouts.default')
@section('content')
{{ $user->name }} - {{ $user->email }}
@stop