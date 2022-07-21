<header class="p-3 bg-dark text-white">
	<div class="container">
		<div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
			<ul class="nav navbar-nav mr-auto float-left">
				<h3>Success App Demo</h3>
			</ul>
			<ul class="nav navbar-nav float-right">
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
			</ul>
		</div>
	</div>
</header>