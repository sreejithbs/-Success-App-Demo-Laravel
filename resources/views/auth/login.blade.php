@extends('auth._partials.master')
@section('page_title', 'Login')

@section('content')

    <h1 class="h3 mb-4 fw-normal">Success App Demo Login</h1>

    @include('messages')
    
    <a href="{{ route('login.redirect') }}" class="w-100 btn btn-lg btn-success">Login with GitHub</a>


@endsection