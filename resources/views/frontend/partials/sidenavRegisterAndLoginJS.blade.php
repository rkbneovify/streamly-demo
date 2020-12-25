
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
};
</script>