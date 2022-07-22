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
		<a href="{{ route('home') }}" class="btn btn-primary btn-sm pull-right">
			< Back to Repository Listing
		</a>
	</div>

	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th scope="col">Issue ID</th>
				<th scope="col">Issue Title</th>
				<th scope="col">Issue Description</th>
				<th scope="col" width="15%">Issue URL</th>
				<th scope="col" width="15%">Actions</th>
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
						{!! Str::inlineMarkdown($issue->description) !!}
					</td>
					<td>
						<a href="{{ $issue->reference_url }}" target="_blank" class="btn btn-sm btn-primary"> View in Github </a>
					</td>
					<td class="no-wrap">
						<a href="javascript:void(0);" data-uid="{{ $issue->uid }}" class="editBtn btn btn-sm btn-primary"> Edit </a>
						<a href="javascript:void(0);" data-uid="{{ $issue->uid }}" class="deleteBtn btn btn-sm btn-danger"> Delete </a>
						<form class="deleteForm" action="{{ route('issue.delete', $issue->uid) }}" method="POST" style="display: none;">
							@csrf
							@method('DELETE')
						</form>
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
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">

	jQuery(document).ready(function($) {
		
		let issueEditor = new SimpleMDE({
			element: $("#issue-description")[0],
			autoDownloadFontAwesome: true,
			forceSync: true,
			spellChecker: false
		});

		// Show create issue form
		$("#createBtn").click(function() {
			$("#issueForm").find('input').val('');
			issueEditor.value('');
			$("#issueModalLabel").html('Add New Issue');
			$("#issueModal").modal('show');
		});

		// Show edit issue form
		$(".editBtn").click(function() {
			$.ajax({
				url: "{{ url('issue/edit') }}/" + $(this).attr('data-uid'),
				type: "GET",
				success: function(response) {
					if (response.status) {
						$("#issueForm")[0].reset();
						$("#issueModalLabel").html('Edit Issue');
						$('#issue-title').val(response.issue.title);
						$('#issue_uid').val(response.issue.uid);
						issueEditor.value(response.issue.description);
						// issueEditor.codemirror.refresh();
						$("#issueModal").modal('show');
					}
				},
				error: function(data) {
					console.log("ERROR : ", data);
				}
			});
		});

		// Handle create/edit issue submission
		$("#issueSubmitBtn").click(function() {
			if ($('#issue-title').val().trim() == '') {
				$(".error-span").show();
				return;
			}
			
			let ajax_url, data;
			let issue_id = $('#issue_uid').val();
			const temp_data = {
				'_token': '{{ csrf_token() }}',
				'issue-title': $('#issue-title').val().trim(),
				'issue-description': $('#issue-description').val().trim(),
			}

			if(issue_id == ''){
				ajax_url = "{{ url('issue/store') }}";
				data = Object.assign({}, temp_data, { 'repository-uid': {{ Js::from($repository->uid) }} });
			} else{
				ajax_url = "{{ url('issue/update') }}/" + issue_id;
				data = Object.assign({}, temp_data, { 'issue-uid': parseInt(issue_id) });
			}

			$.ajax({
				url: ajax_url,
				type: "POST",
				data: data,
				success: function(response) {
					if (response.status) {
						$("#issueModal").modal('hide');
						Swal.fire({
							title: 'Success',
							text: response.message,
							icon: 'success',
						});
						setTimeout(function() {
							location.reload();
						}, 1500);
					}
				},
				error: function(data) {
					console.log("ERROR : ", data);
				}
			});
		});

		// Handle delete issue
		$(".deleteBtn").click(function() {
			Swal.fire({
				title: 'Are you sure?',
				text: "You won't be able to revert this operation.",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, delete it!',
				cancelButtonText: 'No, cancel'
			}).then((result) => {
				if (result.isConfirmed) {
					$(this).next("form.deleteForm").submit();
				}
			})
		});

	});
	
</script>
@endpush