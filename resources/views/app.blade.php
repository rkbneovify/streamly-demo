@extends('frontend/partials/master')
    <meta name="csrf-token" content="{{ csrf_token() }}">
{{-- 
    <!DOCTYPE html>
    <html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Streamly</title>
        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head> --}}

    <link rel="stylesheet" href="{{ asset('frontend/bootstrap.min.css') }}" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- <script> -->

    <!-- <link rel="stylesheet" type="text/css" href="https://templates.envytheme.com/noke/default/assets/css/showMoreItems-theme.min.css"> -->

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    
    <!-- <script type="text/javascript" src="https://templates.envytheme.com/noke/default/assets/js/showMoreItems.min.js"></script> -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.16/vue.js"></script>
  

<style>
    #Logout {
        z-index: 999999!important;
        backdrop-filter: brightness(55%);
    }

    nav#mainNav {
        background-color: #004E92;
    }
*,
*::before,
*::after {
   box-sizing: content-box !important; 
}
.page-footer {
    z-index: 99999;
    position: relative;
}
.programme__timing__section {
    z-index: -2!important;
}
/* .programme__header {
    z-index: -1!important;
} */
/* .channel__programme__section {
    z-index: 1!important;
} */
/* Starts */
button.channel__nav__right {
    position: relative;
    z-index: 999 !important;
    left: 747px;
    padding: 11px;
    max-height: 35% !important;
    background-color: #4d5659;
    border-right: 2px solid #464646;
}
button.channel__nav__left {
    position: relative;
    z-index: 999 !important;
    left: 0px;
    padding: 10px;
    max-height: 35% !important;
    background-color: #4d5659;
    border-right: 2px solid #464646;
}
.channel__programme__section {
    position: relative;
    overflow-x:hidden!important;
    scroll-behavior: smooth;
    left: -1px !important;
    float: left !important;
    /* left: -40px !important; */
}

.channel__programme__section::after {
    content: '';
    background: red;
    inset: 0;
    width:2px;
    left:var(--_offset,0px);
    z-index: 999;
    position: absolute;
}
/*.channel__list__section>div[data-v-56b65aa6] {
    min-height: 135px !important;
}*/
/*
.channel__guide__view {
    max-height: 1216px !important;
    overflow-y: scroll !important;
}
*/
.programme__headerDesign {
    display: flex;
    justify-content: space-between;
    background-color: #000;
    color: #fff;
    padding: 0px 8px;
    color: #dbddde;
}
.programme__content {
    margin-top: 8px;
}
.channel_description {
    overflow: hidden;
   text-overflow: ellipsis;
   display: -webkit-box;
   -webkit-line-clamp: 4; 
   -webkit-box-orient: vertical;
}
.pgFontguidehead {
    white-space: break-spaces;
}
/* .channel__list__section>div[data-v-56b65aa6] 
{
        padding: 10px 10px !important;
} */
/* Ends */
.channel__live {
    position: relative;
    z-index: 999 !important;
    left: 63px;
    padding: 0px;
    max-height: 35% !important;
    background-color: #4d5659;
    border-right: 2px solid red;
}   

.programme__header {
    position: sticky;
    top:0;
    height: 64px !important;
}
.programme__header .header__time[data-v-56b65aa6] {
    position: relative;
    box-sizing: border-box!important;
    flex: 0 0 300px;
    padding-left: 10px;
    padding-right: 10px;
    box-sizing: border-box;
    background-color: #4d5659;
    border-right: 2px solid #464646;
    line-height: 55px;
}
.programme__row {
    height: 8.5vw !important;
}
button#loadmore {
    position: relative;
    z-index: 999 !important;
    left: -38px !important;
    content: "Load More" !important;
}
/**/
/*
.pgGuideheight:not(:nth-child(-n+11)) 
{
    display: none !important;
}
.programme__row:not(:nth-child(-n+10)) 
{
    display: none !important;
}
*/
button#loadmore {
    position: absolute;
    top: 1406px !important;
    z-index: 999 !important;
    float: right !important;
    min-width: 5% !important;
    padding: 15px !important;
    left: 118px !important;
    display: none;
}
button#loadmore:after {
    content: "Load More...";
}
/*.programme__timing__section {
    max-height: 1122px !important;
    overflow-y: scroll !important;
}
div#wrap div {
    justify-content: center;
    font-size: 0.8em;
    background-color: #4d5659;
    color: #dbddde;
    font-size: 0.7vw;
    font-weight: 300;
}*/
/*.programme__timing__section {
    z-index: -99 !important;
    position: absolute !important;
}*/
.channel__filter {
    width: 78vw;
    /* position: fixed; */
    z-index: 9999 !important;
    min-height: 4% !important;
    /* top: 80px !important;
    padding: 1.6vw 1vw!important; */
    /* max-width: 957px; */
    overflow: hidden;
}
.programme__header {
    /* margin-top: 102px; */
    /* position: fixed; */
    z-index: 9999;
    max-width: 57.5% !important;
    /* overflow: hidden; */
}
.channel__header {
    border-left:1px solid white;
    background-color: #4d5659;
    position: sticky !important;
    top:0;
    z-index: 9999 !important;
    /* top: 199px; */
    font-size: 0.8em;
    color: #dbddde;
    overflow: hidden !important;
    /* width: 100% !important; */
    max-width: 15.5vw;
}
.programme__timing__section 
{
    /* margin-top: 161px !important; */
}
.channel__list__section {
    /* margin-top: 161px !important; */
}

span.programme.programme--no__data {
    line-height: 45px !important;
    padding: 13px 25px 37px 24px !important;
}
.channel__name__box.pgGuideheight {
    /*min-width: 91%;*/
    /* min-width: 100% !important; */

}

nav#mainNav {
    padding-top: 0.5rem !important;
    padding-bottom: 1rem !important;
    position: fixed;
    z-index: 99999 !important;
}
/**/
/*button#navlefthorizontal {
    position: fixed;
    top: 168px;
    z-index: 99999;
    padding: 10px 12px 10px 1px !important;
    left: 355px;
    justify-content: center;
    font-size: 0.8em;
    background-color: transparent;
    color: #dbddde !important;
    border: 0px !important;
    color: darkorange !important;
}*/
button#navlefthorizontal {
    /* position: fixed; */
    /* top: 197px; */
    width:10px;
    z-index: 99999;
    padding: 41px 14px 23px 1px !important;
    left: 26.4vw;
    justify-content: center;
    font-size: 0.9em;
    background-color: transparent;
    color: #dbddde !important;
    border: 0px !important;
    color: darkorange !important;
}
#navlefthorizontal:hover {
    background-color: #FF712F !important;
    color: #fff !important;
    border-radius: 5px !important;
    vertical-align: middle !important;
    text-align: center;
}
button#navlefthorizontal:after {
    content: "" !important;
}
button#navlefthorizontal 
{
    right: 70vw;
    background-image: url(data:image/svg+xml;base64,PHN2ZyB2aWV3Qm94PSIwIDAgMTAgMTUiIHZlcnNpb249IjEuMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayI+CiAgPGcgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2U9IiNGRjhDMDAiIGZpbGw9InRyYW5zcGFyZW50Ij4KICAgIDxwb2x5bGluZSBwb2ludHM9IjkgMSAyIDcuNSA5IDE0Ij48L3BvbHlsaW5lPgogIDwvZz4KPC9zdmc+Cg==);
    background-position-x: 0%;
    background-repeat: no-repeat !important;
    background-size: 30% 30% !important;
    background-position: 47% 42% !important;
}
#navlefthorizontal:hover 
{
    background-image: url(data:image/svg+xml;base64,PHN2ZyB2aWV3Qm94PSIwIDAgMTAgMTUiIHZlcnNpb249IjEuMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayI+CiAgPGcgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2U9IiNGRkZGRkYiIGZpbGw9InRyYW5zcGFyZW50Ij4KICAgIDxwb2x5bGluZSBwb2ludHM9IjkgMSAyIDcuNSA5IDE0Ij48L3BvbHlsaW5lPgogIDwvZz4KPC9zdmc+Cg==)!important;
}
/*button#navrighthorizontal {
    position: fixed;
    top: 166px !important;
    z-index: 99999 !important;
    left: 954px !important;
    padding: 10px 12px 10px 1px !important;
    justify-content: center;
    font-size: 0.8em;
    background-color: transparent;
    color: #dbddde !important;
    border: 0px !important;
    color: darkorange !important;
}*/
button#navrighthorizontal {
    position: sticky;
    top:0;
    /* top: 197px !important; */
    z-index: 99998 !important;
    /* left: 87vw !important; */
    padding: 41px 14px 23px 1px !important;
    justify-content: center;
    font-size: 0.9em !important;
    background-color: transparent;
    color: #dbddde !important;
    border: 0px !important;
    color: darkorange !important;
}
#navrighthorizontal:hover {
    background-color: #FF712F !important;
    color: #fff !important;
    border-radius: 5px !important;
    vertical-align: middle !important;
    text-align: center;
    background-image: url(data:image/svg+xml;base64,PHN2ZyB2aWV3Qm94PSIwIDAgMTAgMTUiIHZlcnNpb249IjEuMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayI+CiAgPGcgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2U9IiNGRkZGRkYiIGZpbGw9InRyYW5zcGFyZW50Ij4KICAgIDxwb2x5bGluZSBwb2ludHM9IjEgMSA4IDcuNSAxIDE0Ij48L3BvbHlsaW5lPgogIDwvZz4KPC9zdmc+Cg==) !important;
}
button#navrighthorizontal:after {
    content: "" !important;
}
#navrighthorizontal {
    right: -100%;
    background-image: url(data:image/svg+xml;base64,PHN2ZyB2aWV3Qm94PSIwIDAgMTAgMTUiIHZlcnNpb249IjEuMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayI+CiAgPGcgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2U9IiNGRjhDMDAiIGZpbGw9InRyYW5zcGFyZW50Ij4KICAgIDxwb2x5bGluZSBwb2ludHM9IjEgMSA4IDcuNSAxIDE0Ij48L3BvbHlsaW5lPgogIDwvZz4KPC9zdmc+Cg==);
    background-position-x: 0%;
    background-repeat: no-repeat !important;
    background-position: 47% 43%;
    background-size: 32% 58% !important;
}
.programme__header {
    grid-area: 1 / 2 / 2 / 3;
    display:flex;
    overflow:hidden;
    scroll-behavior:smooth;
    color:white;
    /* margin-top: 101px; */
    /* position: fixed; */
    z-index: 9999;
    max-width: 62.65vw !important;
    /* overflow: hidden; */
    /* left: 345px !important; */
    color: #dbddde;
    font-size: 0.8em;
}
.header__time {
    text-align: center !important;
    justify-content: center !important;
}
/* .programme__timing__section {
    margin-left: 60px !important;
} */
.pgGuideheight
{
    z-index: 999 !important;
}
button:focus {
    outline: 0px !important;
}
.channel__live1
{
    border: 1px solid red !important;
    min-height: 35% !important;
    z-index: 9995 !important;
    left: 65px !important;
    position: relative;
}
.active,.active1
{
    border: 1px solid blue !important; 
    background-color: rgb(0, 0, 231) !important;
    background-color: #1b62cc !important;
    color: #fff5f5 !important;
    border: 1px solid #6894d4 !important;
}
.header__time.active
{
    border: 1px solid blue !important; 
    background-color: rgb(0, 0, 231) !important;
    background-color: #1b62cc !important;
    color: #fff5f5 !important;
    border: 1px solid #6894d4 !important;
}
button#navrighthorizontal {
    grid-area: 1 / 2 / 2 / 3;
    margin-inline-start: auto;
    width:4px;
    background: #FF712F !important;
    background-image: url(data:image/svg+xml;base64,PHN2ZyB2aWV3Qm94PSIwIDAgMTAgMTUiIHZlcnNpb249IjEuMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayI+CiAgPGcgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2U9IiNGRkZGRkYiIGZpbGw9InRyYW5zcGFyZW50Ij4KICAgIDxwb2x5bGluZSBwb2ludHM9IjEgMSA4IDcuNSAxIDE0Ij48L3BvbHlsaW5lPgogIDwvZz4KPC9zdmc+Cg==) !important;
    background-repeat: no-repeat !important;
    background-size: 40% 40% !important;
    background-position: 7px 17px !important;
}
button#navlefthorizontal {
    position: sticky;
    inset: 0;
    top:0;
    width:4px;
    grid-area: 1 / 2 / 2 / 3;
    background: #FF712F !important;
    background-image: url(data:image/svg+xml;base64,PHN2ZyB2aWV3Qm94PSIwIDAgMTAgMTUiIHZlcnNpb249IjEuMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayI+CiAgPGcgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2U9IiNGRkZGRkYiIGZpbGw9InRyYW5zcGFyZW50Ij4KICAgIDxwb2x5bGluZSBwb2ludHM9IjkgMSAyIDcuNSA5IDE0Ij48L3BvbHlsaW5lPgogIDwvZz4KPC9zdmc+Cg==)!important;
    background-repeat: no-repeat !important;
    background-size: 40% 40% !important;
    background-position: 7px 17px !important;
    
}
.programme__details__view {
    position: fixed;
    z-index: 999999 !important;
}
.channel__details__container {
    z-index: 99999;
}
.programme__timing__section {
    z-index: 999!important;
}
.programme__header[data-v-0905d6f1] {
    display: none!important;
}
.currentTZflex {
    display: flex;
    align-items: center;
}
.currentTZ {
    display: block!important;
}
.statusTZ {
	margin-left: 15px;
	padding: 6px 25px;
    border: 1px solid #d5bebe;
    border-radius: 25px;
}
.iconTZ {
    width: 15px;
    height: 15px;
    margin-right: 5px;
}
.tzwidth {
    width: 135px;
}
@media only screen and (max-width: 599px) {
  .channel__header {
    /* width: 115px!important; */
    top: 295px!important;
  }
  /* button#navlefthorizontal {
    padding: 40px 25px 23px 1px !important;
    left: 29vw!important;
    top: 236px;
  } */
  button#navrighthorizontal {
    padding: 40px 25px 23px 1px !important;
    top: 291px !important;
    z-index: 99998 !important;
  }
  /* .programme__header {
    left: 175px !important;
} */
.pgGuideheight {
    height: 20vw!important;
    width: 60px !important;
}
.programme__row {
    height: 20vw!important;
}
.channel__header {
    width: 20vw!important;
    max-width: 100%!important;
    padding: 8px 2.1vw !important;
}
.channel__filter {
    width: 72.9vw!important;
    top: 65px!important;
  }
  .channel__section {
    width: 82.5vw!important;
  }
  .channel__list__section {
    margin-top: 258px !important;
  }
  .channel__programme__section {
    margin-top: 78px!important;
  }
  .pgGuideheight{
    padding: 0px 10px !important;
}
.programme__timing__section {
    /* margin-top: 179px !important; */
}
.programme__header {
    margin-top: 118px!important;
}
}
@media only screen and (min-width: 550px) and (max-width: 599px) {
    button#navrighthorizontal {
    left: 88vw !important;
    right: 12.5vw;
}
}
@media only screen and (min-width: 500px) and (max-width: 549px) {
    button#navrighthorizontal {
    /* left: 87.5vw !important; */
    /* right: 12.5vw; */
}
}
@media only screen and (min-width: 440px) and (max-width: 499px) {
    button#navrighthorizontal {
    /* left: 87vw !important; */
    /* right: 12.5vw; */
}
}
@media only screen and (min-width: 400px) and (max-width: 439px) {
    button#navrighthorizontal {
    /* left: 86.8vw !important; */
    /* right: 13vw; */
}
}
@media only screen and (min-width: 400px) and (max-width: 599px) {
    .channel__header {
    width: 22vw!important;
    max-width: 100%!important;
    padding: 08px 1.5vw !important;
  }
.pgGuideheight {
    width: 22vw!important;
    max-width: 100%!important;
    padding: 00px 1.5vw !important;
}
.programme__header {
    width: 56.42vw!important;
    max-width: 100% !important;
    margin-left: -1px;
}
.channel__filter{
    padding: 5.4vw 4.6vw!important;
}
button#navlefthorizontal {
    padding: 40px 25px 23px 1px !important;
    left: 35vw!important;
    top: 291px;
}
}
@media only screen and (min-width: 600px){
    button#navrighthorizontal {
    /* left: 87vw !important; */
}
}
@media only screen and (max-width: 399px) {
    .channel__header {
    width: 30vw!important;
    max-width: 100%!important;
    padding: 08px 1.5vw !important;
  }
.pgGuideheight {
    width: 30vw!important;
    max-width: 100%!important;
    padding: 00px 1.5vw !important;
}
.programme__header {
    width: 48.42vw!important;
    max-width: 100% !important;
    margin-left: -1px;
}
.channel__filter {
    padding: 7.4vw 4.6vw!important;
}
button#navlefthorizontal {
    right: 56.2vw!important;
    left: 43vw!important;
}
button#navlefthorizontal {
    top: 292px!important;
}
#navrighthorizontal {
    right: 16.8vw!important;
}
button#navrighthorizontal {
    left: 86vw !important;
}
}
@media only screen and (min-width: 360px) and (max-width: 399px) {
    #navrighthorizontal {
    right: 16.8vw!important;
}
button#navrighthorizontal {
    left: 86vw !important;
} 
}
@media only screen and (min-width: 320px) and (max-width: 359px){
    #navrighthorizontal {
    right: 16.8vw!important;
}
button#navrighthorizontal {
    left: 85vw !important;
} 
}
@media only screen and (max-width: 319px){
    #navrighthorizontal {
    right: 16.8vw!important;
}
button#navrighthorizontal {
    left: 84.5vw !important;
} 
}
@media only screen and (min-width: 600px) {
.pgGuideheight {
    height: 8.3vw !important;
    width: 15.5vw !important;
}
.programme__row {
    height: 8.5vw !important;
}
.channel__list__section>div[data-v-56b65aa6] 
{
        padding: 0.6vw 10px;
}
.overflow.newGuidefont {
    white-space: break-spaces !important;
}
.channel__header {
    width: 100% !important;
    padding: 10px 10px!important;
}
}
@media only screen and (max-width: 1170px) {
    .channel__list__section{
    flex: 0 0 100px!important;
}
}
@media only screen and (min-width: 992px) and (max-width: 1240px) {
    .channel__list__section{
    flex: 0 0 100px;
}
.channel__filter {
    top: 80px !important;
    padding: 2vw 1vw!important;
}
}
@media only screen and (min-width: 1241px){
    .channel__filter {
    top: 80px !important;
    padding: 1.6vw 1vw!important; 
    }
}
@media only screen and (min-width: 760px) and (max-width: 991px){
    .channel__filter {
    top: 68px !important;
    padding: 3.4vw 1vw!important; 
    }
}
@media only screen and (min-width: 1700px){
.channel__filter{
    top: 74px !important;
}}
@media only screen and (min-width: 2000px){
.channel__filter{
    top: 68px !important;
}}
@media only screen and (min-width: 760px){
.statusTZ{
    font-size: 14px;
}}
</style>


    @section('content')
    <section class="page-section custom-row">
    
    @if(session()->has('message'))
     <div class="alert alert-success alert-dismissible ">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session()->get('message') }}
    </div>
    @endif
    @if(session()->has('message'))
     <div class="alert alert-danger alert-dismissible ">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session()->get('message') }}
    </div>
    @endif
    
       
        
            {{-- {{ Auth::user()->name }}
          <p>{{$login}} </p>   --}}
                <div id="app">
                    <example-component></example-component>
                </div>

                
                    
    
    
    
            
        
        </section>
    
@endsection
{{-- <script async src="{{ asset('frontend/jquery-3.5.1.min.js') }}" ></script>  --}}
 {{-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> --}}
   
{{-- <script defer src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.5/jquery-ui.min.js'></script> --}}
{{-- <script async src="{{ asset('frontend/jquery-ui.min.js') }}" ></script>  --}}
{{-- <script defer src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script> --}}

<script async src="{{ asset('frontend/popper.min.js') }}" ></script> 
<!-- <script async src="{{ asset('frontend/bootstrap.min.js') }}" ></script> -->
<link rel="stylesheet" href="{{ asset('frontend/style.css') }}" />
<script>window.addEventListener('load', function() {
     
    jQuery('.logouttheuser').on('click', function(){
        jQuery('#Logout').modal('show');
    });
    
})</script>
{{-- <script  src="{{ asset('frontend/script.js') }}" ></script> --}}
<script defer src="{{ asset('js/app.js') }}"></script>
<script>
    window.addEventListener("keydown", function(e) {
    // space and arrow keys
    if([32, 37, 38, 39, 40].indexOf(e.keyCode) > -1) {
        e.preventDefault();
    }
}, false);
    var action = "{{ env("APP_URL") }}"
    function logoutUser() {
    window.location.href = '/logout';
}
//console.log(Intl.DateTimeFormat().resolvedOptions().timeZone)
</script>
