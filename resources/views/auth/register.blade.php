@extends("layouts.auth")

@section('content')
<div class="authentication">
	<div class="container">
		@if($errors->any())
			<div class="alert alert-danger alert-dismissible fade show" role="alert">
				<span class="alert-text text-white"> {{ __('messages.auth.email_or_password_error') }}
				</span>
				<button type="button" class="close" data-dismiss="alert" aria-label="close"
					style="background-color: #ffc107;">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
		@endif
		{{ isset($error) ? $error : '' }}
		<div class="row">
			<div class="col-lg-4 col-sm-12">

				<form method="POST" action="{{ route('register') }}" role="form"
					class="card auth_form">
					@csrf
					<div class="header">
						<img class="logo" src="assets/images/logo.svg" alt="">
						<img class="logo" src="assets/images/a.png" alt="">
						<h5>会員登録</h5>
					</div>
					<div class="body">
						<div class="input-group mb-3">
							<input type="text" class="form-control"
								placeholder="{{ __('messages.profile.name') }}" name="family_name">
							<div class="input-group-append">
								<span class="input-group-text"><i class="zmdi zmdi-account-circle"></i></span>
							</div>
						</div>
						@error('family_name')
							<small class="text-danger text-xs">{{ $message }}</small>
						@enderror
						<div class="input-group mb-3">
							<input type="email" class="form-control" placeholder="Email" name="email">
							<div class="input-group-append">
								<span class="input-group-text"><i class="zmdi zmdi-email"></i></span>
							</div>
						</div>
						@error('email')
							<small class="text-danger text-xs">{{ $message }}</small>
						@enderror
						<div class="input-group mb-3">
							<input type="password" id="password" class="form-control" placeholder="{{ __('messages.profile.password') }}" name="password">
							<div class="input-group-append">
								<span class="input-group-text" id="pass-show"><i class="zmdi zmdi-lock"></i></span>
							</div>
						</div>
						@error('password')
							<small class="text-danger text-xs">{{ $message }}</small>
						@enderror
						<div class="input-group mb-3">
							<input type="password" id="password_confirmation" class="form-control" placeholder="{{ __('messages.profile.confirm_password') }}" name="password_confirmation">
							<div class="input-group-append">
								<span class="input-group-text" id="pass-show-con"><i class="zmdi zmdi-lock"></i></span>
							</div>
						</div>
						@error('password_confirmation')
							<small class="text-danger text-xs">{{ $message }}</small>
						@enderror
						<!-- <div class="checkbox">
							<input id="remember_me" type="checkbox">
							<label for="remember_me">I read and agree to the <a href="javascript:void(0);">terms of
									usage</a></label>
						</div> -->
						<button type="submit" class="btn btn-primary btn-block">{{ __('messages.action.register') }}</button>
						<div class="signin_with mt-3">
							<a href="{{ route('login') }}">アカウントをお持ちですか？</a>
						</div>
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
					<img src="assets/images/signup.svg" alt="Sign Up" />
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

		$("#pass-show-con").on('click', function (event) {

			event.preventDefault();
			if ($('#password_confirmation').attr("type") == "text") {
				$('#password_confirmation').attr('type', 'password');
			} else if ($('#password_confirmation').attr("type") == "password") {
				$('#password_confirmation').attr('type', 'text');
			}
		});
	});

</script>
