<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Streamly Checkout</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js">
    </script>
<script src=
"https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js">
    </script>
<style type="text/css">
  body 
  {
    font-family: Arial;
    font-size: 17px;
    padding: 8px;
    background-color: #008cdd;
  }

  * 
  {
     box-sizing: border-box;
  }

  .row 
  {
     display: -ms-flexbox;
     display: flex;
     -ms-flex-wrap: wrap;
     flex-wrap: wrap;
     margin: 0 -16px;
  }

  .col-25 
  {
    -ms-flex: 25%;
    flex: 25%;
  }

  .col-50 
  {
    -ms-flex: 50%;
    flex: 50%;
  }

  .col-75 
  {
     -ms-flex: 75%;
     flex: 75%;
  }

  .col-25,
  .col-50,
  .col-75 
  {
    padding: 0 16px;
  }

  .container 
  {
     background-color: #f2f2f2;
     padding: 5px 20px 15px 20px;
     border: 1px solid lightgrey;
     border-radius: 3px;
  }

  input[type=text],select 
  {
     width: 100%;
     margin-bottom: 20px;
     padding: 12px;
     border: 1px solid #ccc;
     border-radius: 3px;
  }
  input[type=password],select 
  {
     width: 100%;
     margin-bottom: 20px;
     padding: 12px;
     border: 1px solid #ccc;
     border-radius: 3px;
  }

  label 
  {
     margin-bottom: 10px;
     display: block;
  }

  .icon-container 
  {
      margin-bottom: 20px;
      padding: 7px 0;
      font-size: 24px;
  }

  .btn 
  {
     background-color: #04AA6D;
     color: white;
     padding: 12px;
     margin: 10px 0;
     border: none;
     width: 100%;
     border-radius: 3px;
     cursor: pointer;
     font-size: 17px;
  }

  .btn:hover 
  {
     background-color: #45a049;
  }

  a 
  {
    color: #2196F3;
  }

  hr 
  {
     border: 1px solid lightgrey;
  }

  span.price 
  {
     float: right;
     color: grey;
  }
  .loader-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent black background */
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 9999; /* Ensure it's above other elements */
  }

  .loader {
    border: 4px solid #f3f3f3;
    border-top: 4px solid #3498db;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    animation: spin 2s linear infinite;
    margin: auto;
    margin-top: 45vh;
  }

  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }

  .popup {
    display: none;
    position: fixed;
    top: 48%;
    left: 50%;
    transform: translate(-50%, -50%);
    padding: 20px;
    background-color: white;
    border-radius: 5px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
    z-index: 1001; /* Above loader */
    text-align: center;
    color: red;
  }
  .popup-content {
    text-align: center;
  }

  .popup-button {
    margin-top: 10px;
  }
  @media (max-width: 800px) 
  {
     .row 
     {
         flex-direction: column-reverse;
     }
     .col-25 
     {
          margin-bottom: 20px;
     }
  }
</style>
</head>
<body>
<img src="/frontend/img/streamly-logo.png" alt="streamly">
<h2 style="text-align: center;">Billing Address And Payment Details</h2>
<div class="row">
  <div class="col-75">
    <div class="container">
      <form action="/checkout/backendpayment" id="frmpayment" method="post">
      <input type="hidden" name="_method" value="POST">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="hidden_id" value="<?php echo $plandetails_masteruser->id; ?>">
        <input type="hidden" name="quantity" value="<?php echo $plandetails_masteruser->device_limit; ?>">
        <input type="hidden" name="period" value="<?php echo $plandetails_masteruser->period; ?>">
        <div class="row">
          <div class="col-50">
            <h3>Billing Address</h3>
            <!-- <label for="pid"><i class="fa fa-user"></i> Person ID</label> -->
            <input type="hidden" id="pid" name="pid" placeholder="0" value="<?php echo $masteruserData->id; ?>" readonly>
            <label for="fullname"><i class="fa fa-user"></i> Full Name</label>
            <input type="text" id="fullname" name="fullname" placeholder="Faraz Shah" value="<?php echo $masteruserData->name; ?>" readonly>
            <label for="email"><i class="fa fa-envelope"></i> Email</label>
            <input type="text" id="email" name="email" placeholder="codewithfaraz@example.com" value="<?php echo $masteruserData->email; ?>" readonly>
            <label for="phone"><i class="fa fa-address-card-o"></i> Phone</label>
            <input type="text" id="phone" name="phone" placeholder="9904325689" value="<?php echo $plandetails_masteruser->phone; ?>" readonly>
            
            <label for="payType"><i class="fa fa-institution"></i> Payment-Type</label>
            <input type="text" id="paytype" name="paytype" placeholder="charge" value="<?php echo $plandetails_masteruser->payment_type;?>" readonly>

            <label for="Address"><i class="fa fa-address-card"></i> Address</label>
            <input type="text" id="address" name="address" required>
            
            <div class="row">
            <div class="col-50">
            <label for="City">City</label>
            <input type="text" id="city" name="city" required>
            </div>
            <div class="col-50">
            <label for="State">State</label>
            <input type="text" id="state" name="state" required>
            </div>
            </div>
            <label for="Country"><i class="fa fa-globe"></i> Country</label>
            <input type="text" id="country" name="country" required>
            
            <div class="row">
              <div class="col-50">
                
              </div>
              <div class="col-50">
               
              </div>
            </div>
          </div>

          <div class="col-50">
            <h3>Payment</h3>
            <label for="fname">Accepted Cards</label>
            <div class="icon-container">
              <i class="fa fa-cc-visa" style="color:navy;"></i>
              <i class="fa fa-cc-amex" style="color:blue;"></i>
              <i class="fa fa-cc-mastercard" style="color:red;"></i>
              <i class="fa fa-cc-discover" style="color:orange;"></i>
            </div>
            
            <label for="ccnum">Credit card number</label>
            <input type="text" id="ccnum" name="cardnumber" placeholder="1111-2222-3333-4444" onkeypress="validate(event)" required>
            <label for="expmonth">Exp Month</label>
           
            <select name="expmonth" id="expmonth">
              <option value="01" selected>January</option>
              <option value="02">February</option>
              <option value="03">March</option>
              <option value="04">April</option>
              <option value="05">May</option>
              <option value="06">June</option>
              <option value="07">July</option>
              <option value="08">August</option>
              <option value="09">September</option>
              <option value="10">October</option>
              <option value="11">November</option>
              <option value="12">December</option>
            </select>
            <div class="row">
              <div class="col-50">
                <label for="expyear">Exp Year</label>
                <input type="text" id="expyear" name="expyear" placeholder="2027" maxlength="4" onkeypress="validate(event)" onblur="isValidExpiryYear(this.value);" required>
                <p id="errorMessage" style="color: red; display: none; font-size:0.8rem; margin: 0px;">Warning: Card year is not valid</p>
              </div>
              <div class="col-50">
                <label for="cvv">CVV</label>
                <input type="password" id="cvv" name="cvv" placeholder="123" maxlength="3" onkeypress="validate(event)" required>
              </div>
            </div>
          </div>

        </div>
        <label>
          <input type="hidden" name="txthprice" id="txthprice" value="<?php echo $plandetails_masteruser->amount; ?>">
          <input type="checkbox" checked="checked" name="sameadr"> Shipping address same as billing
        </label>
        <input type="submit" value="Proceed To Payment" class="btn">
        <div class="loader-container" id="loaderContainer">
          <div class="loader"></div>
        </div>
      </form>
      <div class="popup" id="popup">
          <div class="popup-content" id="popupContent">
            <!-- Popup content will be added dynamically -->
          </div>
        </div>
    </div>
  </div>
  <div class="col-25">
    <div class="container">
      <h4>Cart <span class="price" style="color:black"><i class="fa fa-shopping-cart"></i><b>1</b></span></h4>
      <hr>
      <p>Total <span class="price" id="price" style="color:black"><b>$<?php echo $plandetails_masteruser->amount / 100; ?></b></span></p>
    </div>
  </div>
</div>
<script type="text/javascript">
  function validate(evt) 
  {
      var theEvent = evt || window.event;
      if (theEvent.type === 'paste') 
      {
           key = event.clipboardData.getData('text/plain');
      } 
      else 
      {
           var key = theEvent.keyCode || theEvent.which;
           key = String.fromCharCode(key);
      }
      var regex = /[0-9]|\./;
      if( !regex.test(key) ) 
      {
        theEvent.returnValue = false;
        if(theEvent.preventDefault) theEvent.preventDefault();
      }

}
function isValidExpiryYear(expiryYear) 
{
  //alert(expiryYear);
  const currentYear = new Date().getFullYear();
  const parsedYear = parseInt(expiryYear, 10);
  var errorMessage = document.getElementById("errorMessage");

  // Ensure the input is a valid year and not in the past
  if(!isNaN(parsedYear) && parsedYear < currentYear)
  {
     document.getElementById("expyear").focus();
     errorMessage.style.display = "block";
     return false;
  }
  errorMessage.style.display = "none";
  return true;
}

document.addEventListener('contextmenu', function(e) {
  e.preventDefault();
});

document.addEventListener('keydown', function(e) {
  if (e.key === 'F12') {
    e.preventDefault();
  }
});

$(":input").inputmask();

$("#ccnum").inputmask({"mask": "9999 9999 9999 9999"});

</script>
<script>
$(document).ready(function() {
  $("#frmpayment").submit(function(event) {
    event.preventDefault(); // Prevent default form submission

    // Show loader
    $("#loaderContainer").show();

    // Collect form data
    var formData = $(this).serialize();
    
    // Perform AJAX call
    $.ajax({
      type: "POST",
      url: "/checkout/backendpayment", // Adjust the URL
      data: formData,
      success: function(response) {
        // Hide loader on success
        $("#loaderContainer").hide();

        // Handle success response here
        console.log("Form submitted successfully", response);

        if(response == 'payment success'){
          window.location.href = '/success';
        }else{
        var popupHtml = '<p>Error: ' + response + '</p><button class="popup-button" id="popupOk">OK</button>';
        $("#popup").html(popupHtml).show();
        }
        // You can also update the UI or show a success message here
      },
      error: function(xhr, status, error) {
        // Hide loader on error
        $("#loaderContainer").hide();

        // Handle error here
        console.error("Error submitting form", error);
        var popupHtml = '<p>Error: ' + error + '</p><button class="popup-button" id="popupOk">OK</button>';
        $("#popup").html(popupHtml).show();
        // You can also show an error message to the user here
      }
    });
    // Hide popup when "OK" button is clicked
    $(document).on("click", "#popupOk", function(event) {
      $("#popup").hide();
    });
  });
});

</script>
</body>
</html>