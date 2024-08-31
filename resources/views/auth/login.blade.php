@extends("layouts.auth")

@section("content")

<div class="authentication">
	<div class="container">
		@if($errors->any())
			<div class="alert alert-danger alert-dismissible fade show" role="alert">
				<span class="alert-text text-white">{{ __('messages.auth.email_or_password_error') }}</span>
				<button type="button" class="close" data-dismiss="alert" aria-label="close" style="background-color: #ffc107;">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
		@endif
		<div class="row">
			<div class="col-lg-4 col-sm-12">
				{{ isset($error) ? $error : '' }}
				<form method="POST" action="{{ route('login') }}" role="form" class="card auth_form">
					@csrf
					<div class="header">
						<!-- <img class="logo" src="assets/images/logo.svg" alt=""> -->
						<img class="logo" src="assets/images/a.png" alt="">
						<h5>ログイン</h5>
					</div>
					<div class="body">
						<div class="input-group mb-3">
							<input type="email" id="email" class="form-control" placeholder="Email" name="email">
							<div class="input-group-append">
								<span class="input-group-text"><i class="zmdi zmdi-account-circle"></i></span>
							</div>
						</div>
						<div class="input-group mb-3">
							<input type="password" id="password" class="form-control" placeholder="{{ __('messages.profile.password') }}" name="password">
							<div class="input-group-append">
								<span class="input-group-text"><a id="pass-show" class="forgot" title="Forgot Password"><i class="zmdi zmdi-lock"></i></a></span>
							</div>
						</div>
						@error('password')
							<small class="text-danger text-xs">{{ $message }}</small>
						@enderror
						<!-- <div class="checkbox">
							<input id="remember_me" type="checkbox">
							<label for="remember_me">Remember Me</label>
						</div> -->
						<!-- <p class="mb-1 text-xs rt">
							<a href="forgot-password.html">{{ __('messages.auth.forget_password') }}</a>
						</p> -->
						<button type="submit" class="btn btn-primary btn-block mt-4">{{ __('messages.auth.login') }}</button>
						<p class="signin_with mt-3">
							<a href="{{ url('/register') }}">{{ __('messages.auth.have_no_account') }}</a>
						</p>
						<!-- <div class="signin_with mt-3">
							<button class="btn btn-primary btn-icon btn-icon-mini btn-round facebook"><i class="zmdi zmdi-facebook"></i></button>
							<button class="btn btn-primary btn-icon btn-icon-mini btn-round twitter"><i class="zmdi zmdi-twitter"></i></button>
							<button class="btn btn-primary btn-icon btn-icon-mini btn-round google"><i class="zmdi zmdi-google-plus"></i></button>
						</div> -->
					</div>
				</form>
				<div class="copyright text-center">
					&copy;
					<script>
						document.write(new Date().getFullYear())
					</script>,
					<span><a href="#">AMAZON</a></span>
				</div>
			</div>
			<div class="col-lg-8 col-sm-12">
				<div class="card">
					<img src="assets/images/signin.svg" alt="Sign In" />
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>

<script>
	$(document).ready(function () {
		$("#pass-show").on('click', function (event) {

			event.preventDefault();
			if ($('#password').attr("type") == "text") {
				$('#password').attr('type', 'password');
			} else if ($('#password').attr("type") == "password") {
				$('#password').attr('type', 'text');
			}
		});
	});
</script>
