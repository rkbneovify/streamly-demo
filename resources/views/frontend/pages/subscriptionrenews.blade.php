<?php 
use Laravel\Cashier\Cashier;?>
@extends('frontend/partials/master')

@section('top-stylesheets')
@include('frontend.partials.sidenavRegisterAndLoginCSS')
@endsection
<style>
    .headerexp {
        text-align: center;
        width: 50%;
        margin: auto;
    }
    .masthead {
        padding-top: 7.50rem!important;
        background-image: url(../frontend/img/bannerblur.png)!important;
    }
    #mainNav {
        background-color: #837e7e1c!important;
    }
    .buttonUpgrade {
        padding: 10px 26px;
        background: #FF712F;
        border: none;
        color: #fff;
        font-weight: 500;
    }
    *:focus {
    outline: 0 !important;
    background: #fff;
    color: #000;
    }
</style>
@include('frontend.partials.sidenavRegisterAndLogin')
<!-- Masthead-->
<header class="masthead">
	    <div class="container">
	        <div class="masthead-heading headerexp">
                <img src="{{ asset('frontend/img/exp.png') }}"  loading="lazy" alt="logo of streamly" class="img-fluid" />
                </div>
                <div class="headerexp">
                <p>Your subscription has expired</p>
                <p>To get a subscription please click on a upgrade plan button below or contact at streamlytv@admin.com</p>
                <button class="buttonUpgrade" type="button" onclick="location.href='/subscription';">Upgrade Plan</button>
            </div>
	    </div>
	</header>
@section('stylesheets-bottom')
@include('frontend.partials.sidenavRegisterAndLoginJS')
<script>

    var botmanWidget = {
    title:'Streamly Bot',
    introMessage: 'Hello, I am a Streamly Bot! I am here to assist you and answer all your questions about our products and services!',
    mainColor:'#004E92',
    aboutText:'',
    bubbleBackground:'#004E92',
    headerTextColor: '#fff',
};

</script>


<script defer src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js'></script>
@endsection
@section('stylesheets')