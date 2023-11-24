<!-- resources/views/password_confirmation.blade.php -->
@yield('top-stylesheets')
	<link rel="stylesheet" href="{{ asset('frontend/bootstrap.min.css') }}" />
    <script defer src="{{ asset('frontend/popper.min.js') }}" ></script> 
<script defer src="{{ asset('frontend/jquery-1.12.1-ui.js') }}" ></script> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Confirmation</title>
    <style>
        /* Add your styles here */
        .modal {
            display: block;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div id="passwordModal" class="modal" data-bs-backdrop="static" 
            data-bs-keyboard="false" 
            tabindex="-1"
            aria-labelledby="aboutUsLabel" 
            aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        @if(session()->has('error'))
			  <div class="alert alert-danger">
			  <li>{{ session()->get('error') }}</li>
			  </div>
              @endif
            <form action="{{ url("/userupdatepass", [$id]) }}" id="passwordForm" class="text-left" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
					
						<div class="form-group">
							<label for="oldpassword">Old Password</label>
							<input type="password" class="form-control" name="old_password" required ="oldpassword">
						</div>

						<div class="form-group">
							<label for="newpassword">New Password</label>
							<div>
							<input type="password" class="form-control" name="password" required id="newpassword">
							<span class="bi bi-eye-slash field_icon" id="togglePasswordnew"></span>
							</div>
						</div>

						<div class="form-group">
							<label for="cnewpassword">Confirm New Password</label>
					
						
							<div>
							<input type="password" class="form-control" name="password_confirmation" required id="cnewpassword">
							<span class="bi bi-eye-slash field_icon" id="togglePassword"></span>
							</div>
						</div>

						<div class="text-center">
							<button type="submit" class="btn btn-lg login-btn">
								Update
							</button>
						</div>
					</form>
        </div>
    </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const passwordModal = document.getElementById('passwordModal');
        const passwordForm = document.getElementById('passwordForm');
        // passwordModal.addEventListener('click', (event) => {
        //     // Check if the click is outside the modal content
        //     if (!event.target.closest('.modal-content')) {
        //         passwordModal.style.display = 'none';
        //     }
        // });

        passwordForm.addEventListener('submit', (event) => {
            // Add your form validation logic here
            // For example, check if the passwords match
            const password = document.getElementById('newpassword').value;
            const confirmPassword = document.getElementById('cnewpassword').value;

            if (password !== confirmPassword) {
                alert('Passwords do not match');
                event.preventDefault();
            }
        });
    });
</script>
</body>
</html>