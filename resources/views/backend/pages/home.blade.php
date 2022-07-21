@extends('backend._partials.master')
@section('page_title', 'Repository List')

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
						{{ $repository->uid }}
					</td>
					<td>
						{{ $repository->name }}
					</td>
					<td>
						{{ ucfirst($repository->visibility) }}
					</td>
					<td>
						<a href="{{ $repository->reference_url }}" target="_blank" class="btn btn-sm btn-primary"> View in Github </a>
					</td>
					<td>
						<a href="{{ route('issue.list', $repository->uid) }}" class="btn btn-sm btn-primary"> View Issues </a>
					</td>
				</tr>
			@empty
				<tr>
					<td colspan="5"> No Repositories found </td>
				</tr>
			@endforelse
		</tbody>
	</table>

</div>
@endsection