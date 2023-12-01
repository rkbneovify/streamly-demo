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
      .period {
        display: block;
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
@elseif(session()->has('info'))
<div class="alert alert-success alert-dismissible ">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		{{ session()->get('info') }}
</div>
@endif
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Create Payment Link For Master Biller</h1> 
          </div>
         
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Create Payment</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <div class="card">
    <div style="" class="card-header">
    <div>
    <div class="form-container">
    <form action="{{ url('salesuser/payment_links') }}" enctype="multipart/form-data" method="post">
    <input type="hidden" name="_method" value="POST">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <label for="name">Master Biller Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Master Biller Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="phone">Master Biller Phone:</label>
            <input type="text" id="phone" name="phone" maxlength="15" required>
            <span id="error-msg" style="color: red;"></span>

            <label for="website">Device Limit:</label>
            <input type="number" id="device_limit" name="device_limit" min="5" max="100" value="5" oninput="validateInput()" required>

            <p id="errorMessage" style="color: red; display: none; font-size:0.8rem;">* Value must be 5 or greater.</p>

            <div class="input-box">
            <label>
                 <input type="radio" name="payment_type" checked="checked" value="subscription" onclick ="periodIntervalShow()">Subscription
            </label>
            <label>
                 <input type="radio" name="payment_type" value="charge"  onclick ="periodIntervalNone()" required>Charge
            </label>
            </div>
            <div class="period" id="period">
            <label for="period">Time Interval:</label>
            <select id="period" name="period">
            <option value="week">Week</option>
            <option value="month">Month</option>
            <option value="year">Year</option>
            </select>
            </div>
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" required></textarea>

            <label for="amount">Amount (USD):</label>
            <div style="display: flex;">
                <span style="padding: 8px;">US$</span>
                <input type="number" id="amount" min="1" name="amount" oninput="handleInput(this)" pattern="\d*" required style="flex: 1;">
            </div>
            <p id="errorMessageprice" style="color: red; display: none; font-size:0.8rem;">* Value must be 1 or greater.</p>

            <button type="submit">Submit</button>
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
  function periodIntervalShow(){
    var periodValue = document.getElementById("period");
    periodValue.style.display = "block";
  }
  function periodIntervalNone(){
    var periodValue = document.getElementById("period");
    periodValue.style.display = "none";
  }
function validateInput() {
  var input = document.getElementById("device_limit");
  var errorMessage = document.getElementById("errorMessage");

  if (input.value < 5) {
    errorMessage.style.display = "block";
    input.setCustomValidity("Value must be 5 or greater.");
  } else {
    errorMessage.style.display = "none";
    input.setCustomValidity("");
  }
};
const phoneInput = document.getElementById('phone');
const errorMsg = document.getElementById('error-msg');

phoneInput.addEventListener('input', function() {
    const inputValue = phoneInput.value;
    const numericValue = inputValue.replace(/[^0-9]/g, ''); // Remove non-numeric characters

    if (inputValue !== numericValue) {
        phoneInput.value = numericValue;
        errorMsg.textContent = 'Please enter only numeric digits.';
    } else {
        errorMsg.textContent = '';
    }
});

function handleInput(inputElement) {
  var errorMessagePrice = document.getElementById("errorMessageprice");
    if (inputElement.value === "") {
        errorMessagePrice.style.display = "none";
        return; // Allow empty value
    }

    if (inputElement.value < 1) {
        inputElement.value = 1;
        errorMessagePrice.style.display = "block";
    }
    if (inputElement.value > 1) {
        errorMessagePrice.style.display = "none";
    }
}
</script>
@endsection