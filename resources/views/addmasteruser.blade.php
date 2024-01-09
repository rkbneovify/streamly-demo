@extends('master')
@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <style>
      body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .form-container {
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, select, textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        textarea {
            resize: vertical;
        }
        select {
            height: 35px;
        }
        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 3px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .input-box label 
      {
         display: inline-block;
         margin-right: 115px; 
         white-space: nowrap;
      }
      .input-box {
        margin: 20px 00px;
      }
      /* Chrome, Safari, Edge, Opera */
      input::-webkit-outer-spin-button,
      input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
      }

      /* Firefox */
      input[type=number] {
      -moz-appearance: textfield;
      }
    
    </style>
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
      @if(session()->has('error'))
 <div class="alert alert-danger alert-dismissible ">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		{{ session()->get('error') }}
</div>
@elseif(session()->has('error_info'))
<div class="alert alert-danger alert-dismissible ">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		{{ session()->get('error_info') }}<br>
    <a style="color: #fffb8d;" href="/salesuser/resendVerificationEmail/{{session()->get('user_email')}}">Resend verification email</a>
</div>
@elseif(session()->has('info'))
<div class="alert alert-success alert-dismissible ">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		{{ session()->get('info') }}
</div>
@endif
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Add Master User</h1>
          </div>
         
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Add Master User</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <div class="card">
    <div style="" class="card-header">
    <div>
    <div class="form-container">
    <form action="{{ url('salesuser/create_master_user') }}" enctype="multipart/form-data" method="post">
    <input type="hidden" name="_method" value="POST">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="masteruser" value="masteruser">
            <label for="name">Add Master User Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Add Master User Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="phone">Zipcode:</label>
            <!-- <input type="tel" id="zipcode" name="zipcode" required> -->
            <input type="number" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==5) return false;" id="zip-code" name="zipcode" class="form-control" placeholder= "Ex: 92432" min="00000" minlength = "5" oninput="this.value = Math.abs(this.value)" required>

            <label for="website">Password:</label>
            <div class="password-input">
            <input type="password" id="password" name="password" class="form-control" required>
            <div style="display: flex">
            <input style="width: 1.2%;" type="checkbox" id="show-password"> <p style="margin-bottom: 10px; margin-left: 10px; font-size: 1vw;">Show Password</p>
            </div>
            </div>

            <button type="submit">Add Master User</button>
        </form>
</div>
    </div>
    </div>
    </div>


 
    <!-- Main content -->
    <section class="content">

    

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <script>
    const showPasswordCheckbox = document.getElementById('show-password');
    const passwordInput = document.getElementById('password');
    
    showPasswordCheckbox.addEventListener('change', function() {
        if (this.checked) {
            passwordInput.type = 'text';
        } else {
            passwordInput.type = 'password';
        }
    });
</script>
@endsection