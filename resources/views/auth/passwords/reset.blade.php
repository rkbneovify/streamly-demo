@extends('layouts.app')

@section('content')
<style>
    .field_icon{
	float: right;
    margin-right: 10px;
    margin-top: -30px;
    position: relative;
    z-index: 2;
}
.invalid-feedback {
    display: block!important;
}
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />

<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    <form method="POST" id="myForm" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email ?? old('email') }}" required autofocus readonly>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                            <div>
                            <input id="password" minlength="6" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
							<!-- <input type="password" class="form-control" name="password" id="newpassword"> -->
							<span class="bi bi-eye-slash field_icon" id="togglePasswordnew"></span>
							</div>
                               

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <div>
                                <input id="password-confirm" minlength="6" type="password" class="form-control" name="password_confirmation" required>
                                <!-- <input type="password" class="form-control" name="password_confirmation" id="cnewpassword"> -->
							    <span class="bi bi-eye-slash field_icon" id="togglePassword"></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
const togglePasswordnew = document.querySelector("#togglePasswordnew");
        const passwordnew = document.querySelector("#password");
		const togglePassword = document.querySelector("#togglePassword");
        const password = document.querySelector("#password-confirm");

        togglePasswordnew.addEventListener("click", function () {
            // toggle the type attribute
            const type = passwordnew.getAttribute("type") === "password" ? "text" : "password";
            passwordnew.setAttribute("type", type);
            
            // toggle the icon
            this.classList.toggle("bi-eye");
        });
		togglePassword.addEventListener("click", function () {
            // toggle the type attribute
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);
            
            // toggle the icon
            this.classList.toggle("bi-eye");
        });

        // prevent form submit
        const form = document.querySelector("form");
        form.addEventListener('submit', function (e) {
            // e.preventDefault();
            document.getElementById("myForm").submit();
        });
</script>
@endsection
