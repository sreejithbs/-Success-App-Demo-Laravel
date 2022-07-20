@extends('backend._partials.master')
@section('page_title', 'Issues List')

@section('content')
<div class="bg-light p-5 rounded">
	<h2>Admin Dashboard</h2>
	<hr />

	@include('messages')

	<p class="lead">Welcome {{ auth()->user()->name }} (username : {{ auth()->user()->username }})</p>
	<p><strong> List of available issues for repository : {{ $repository->name }} </strong></p>

	<div class="mb-2">
		<a href="" class="btn btn-success btn-sm"> + Create New Issue </a>
	</div>

	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th scope="col">Issue ID</th>
				<th scope="col">Issue Title</th>
				<th scope="col">Issue Description</th>
				<th scope="col">Issue URL</th>
				<th scope="col">Actions</th>
			</tr>
		</thead>
		<tbody>
			@forelse($issues as $issue)
				<tr>
					<td>
						{{ $issue->uid }}
					</td>
					<td>
						{{ $issue->title }}
					</td>
					<td>
						{!! $issue->description !!}
					</td>
					<td>
						<a href="{{ $issue->reference_url }}" target="_blank" class="btn btn-sm btn-primary"> Visit Github </a>
					</td>
					<td>
						<a href="" class="btn btn-sm btn-primary"> Edit </a>
						<a href="" class="btn btn-sm btn-danger"> Delete </a>
					</td>
				</tr>
			@empty
				<tr>
					<td colspan="5"> No Issues found for this repository </td>
				</tr>
			@endforelse
		</tbody>
	</table>

</div>
@endsection