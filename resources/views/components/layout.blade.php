@extends('layouts.app')

@section('content')
    <h1>{{ $title }}</h1>
    {{ $slot }}
@endsection