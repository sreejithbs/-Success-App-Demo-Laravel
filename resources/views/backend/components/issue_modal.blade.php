<div class="modal fade" id="issueModal" tabindex="-1" role="dialog" aria-labelledby="issueModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="issueModalLabel"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="issueForm">
					<div class="form-group">
						<label for="issue-title" class="col-form-label warning">Issue Title *</label>
						<input type="text" class="form-control warning" id="issue-title">
						<span class="text-danger text-left error-span">* This is a required field.</span>
					</div>
					<div class="form-group">
						<label for="issue-description" class="col-form-label">Issue Description</label>
						<textarea class="form-control" id="issue-description"></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="button" id="issueSubmitBtn" class="btn btn-primary">Submit</button>
			</div>
		</div>
	</div>
</div>
