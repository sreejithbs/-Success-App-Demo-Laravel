<header class="p-3 bg-dark text-white">
	<div class="container">
		<div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
			<h3>Success App Demo</h3>
			<ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0"></ul>
			
			@auth
			<div class="text-end">
				<a class="btn btn-outline-light me-" href="" onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
					<i class="ft-power"></i> Logout
				</a>
				<form id="logout-form" action="{{ route('logout.handle') }}" method="POST" style="display: none;">
					@csrf
				</form>
			</div>
			@endauth

		</div>
	</div>
</header>