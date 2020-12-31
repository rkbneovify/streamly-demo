
<script>
    @if ($errors->has('email'))
    // document.getElementById("mySidenav").style.width = "34%";
    document.getElementById("mySidenav").classList.add("show");
@endif
@if ($errors->has('user-email')||$errors->has('user-password')||$errors->has('zip_code')||$errors->has('SMTP-Error')||$errors->has('g-recaptcha-response'))
    // document.getElementById("mySidenav").style.width = "34%";
    document.getElementById("mySidenavRegister").classList.add("show");
@endif

@if(session()->has('message'))
document.getElementById("mySidenavRegister").classList.add("show");
@endif
window.onload = function() {
    var $recaptcha = document.querySelector('#g-recaptcha-response');

    if($recaptcha) {
        $recaptcha.setAttribute("required", "required");
    }

$(document).on('click', '.toggle-password1', function() {

$(this).toggleClass("fa-eye fa-eye-slash");

var input = $("#user-password");
input.attr('type') === 'password' ? input.attr('type','text') : input.attr('type','password');
});
$(document).on('click', '.toggle-password2', function() {

$(this).toggleClass("fa-eye fa-eye-slash");

var input2 = $("#password");
input2.attr('type') === 'password' ? input2.attr('type','text') : input2.attr('type','password');
});
};
</script>
<style>
     
    .field_icon {
        float: right;
        margin-right: 6px;
        margin-top: -25px;
        position: relative;
        z-index: 2;
    }
</style>