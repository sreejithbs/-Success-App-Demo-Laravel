@extends('backend._partials.master')
@section('page_title', 'Admin Dashboard')

@section('content')
<div class="bg-light p-5 rounded">
	<h2>Admin Dashboard</h2>
	<hr />

	@include('messages')

	<p class="lead">Welcome {{ auth()->user()->name }}</p>

</div>
@endsection