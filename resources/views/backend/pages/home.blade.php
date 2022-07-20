@extends('backend._partials.master')
@section('page_title', 'Admin Dashboard')

@section('content')
<div class="bg-light p-5 rounded">
	<h2>Admin Dashboard</h2><hr />

	@include('messages')

	<p class="lead">Welcome {{ auth()->user()->name }} (username : {{ auth()->user()->username }})</p>
	<p><strong> List of available Repositories </strong></p>

	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th scope="col">Repository ID</th>
				<th scope="col">Repository Name</th>
				<th scope="col">Repository Visibility</th>
				<th scope="col">Repository URL</th>
				<th scope="col">Actions</th>
			</tr>
		</thead>
		<tbody>
			@forelse($repositories as $repository)
				<tr>
					<td>
						{{ $repository->repository_id }}
					</td>
					<td>
						{{ $repository->repository_name }}
					</td>
					<td>
						{{ $repository->repository_private ? "Private" : "Public" }}
					</td>
					<td>
						<a href="{{ $repository->repository_url }}" target="_blank" class="btn btn-sm btn-primary"> Visit </a>
					</td>
					<td>
						<a href="" class="btn btn-sm btn-primary"> View Issues </a>
					</td>
				</tr>
			@empty
				<div class="row mt-2">
					<div class="form-group col-md-2">
						No Repositories found
					</div>
				</div>
			@endforelse
		</tbody>
	</table>

</div>
@endsection