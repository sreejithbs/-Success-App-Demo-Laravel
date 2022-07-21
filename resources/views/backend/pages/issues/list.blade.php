@extends('backend._partials.master')
@section('page_title', 'Issues List')

@section('page_styles')
<!-- Markdown Editor Stylesheet -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/highlight.js/latest/styles/github.min.css">
@endsection

@section('content')
<div class="bg-light p-5 rounded">
	<h2>Admin Dashboard</h2>
	<hr />

	@include('messages')

	<p class="lead">Welcome {{ auth()->user()->name }} (username : {{ auth()->user()->username }})</p>
	<p><strong> List of available issues for repository : {{ $repository->name }} </strong></p>

	<div class="mb-2">
		<a href="javascript:void(0);" data-uid="{{ $repository->uid }}" id="createBtn" class="btn btn-success btn-sm">
			+ Add New Issue
		</a>
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
			@forelse($repository->issues as $issue)
			<tr>
				<td>
					{{ $issue->uid }}
				</td>
				<td>
					{{ $issue->title }}
				</td>
				<td>
					{!! Str::markdown($issue->description) !!}
					{!! Str::inlineMarkdown($issue->description) !!}
				</td>
				<td>
					<a href="{{ $issue->reference_url }}" target="_blank" class="btn btn-sm btn-primary"> Visit Github </a>
				</td>
				<td>
					<a href="javascript:void(0);" data-uid="{{ $issue->uid }}" class="btn btn-sm btn-primary"> Edit </a>
					<a href="javascript:void(0);" data-uid="{{ $issue->uid }}" class="btn btn-sm btn-danger"> Delete </a>
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

@include('backend.components.issue_modal')

@stop

@push('page_scripts')
<!-- Markdown Editor Script -->
<script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
<script src="https://cdn.jsdelivr.net/highlight.js/latest/highlight.min.js"></script>

<script type="text/javascript">
	jQuery(document).ready(function($) {

		let issueEditor = new SimpleMDE({
			element: $("#issue-description")[0],
			autoDownloadFontAwesome: true,
			forceSync: true,
		});

		$("#createBtn").click(function() {
			$("#issueForm")[0].reset();
			$("#issueModalLabel").html('Add New Issue');
			$("#issueModal").modal('show');
		});

		$("#issueSubmitBtn").click(function() {
			if ($('#issue-title').val().trim() == '') {
				$(".error-span").show();
				return;
			}

			$.ajax({
				url: "{{ url('issue/store') }}",
				type: "POST",
				data: {
					'_token': '{{ csrf_token() }}',
					'repository-uid': {{ Js::from($repository->uid) }},
					'issue-title': $('#issue-title').val().trim(),
					'issue-description': $('#issue-description').val().trim(),
				},
				success: function(response) {
					if (response.status) {
						$("#issueModal").modal('hide');
						setTimeout(function() {
							alert(response.message);
							location.reload();
						}, 500);
					}
				},
				error: function(data) {
					console.log("ERROR : ", data);
				}
			});
		});

	});
</script>
@endpush