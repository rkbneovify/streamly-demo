<?php 
use Laravel\Cashier\Cashier;?>
@extends('frontend/partials/master')

@section('top-stylesheets')
@include('frontend.partials.sidenavRegisterAndLoginCSS')
@endsection
@section('stylesheets-bottom')
@include('frontend.partials.sidenavRegisterAndLoginJS')
@endsection
@section('stylesheets')
  <style>
 #botmanWidgetRoot>div{
    z-index: 1000 !important;
 }

        .alert.alert-position {
            position: absolute;
            width: 100%;
            z-index: 1040;
        }
        	
	.css-thjkr7,
	[data-css-thjkr7] {
		position: relative;
		display: -webkit-box;
		display: -moz-box;
		display: -ms-flexbox;
		display: -webkit-flex;
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		padding: 0;
		margin: 0;
		/* background: -webkit-linear-gradient(98deg, #000428 0%, #004E92 100%);
		background: -moz-linear-gradient(98deg, #000428 0%, #004E92 100%);
		background: transparent linear-gradient(98deg, #000428 0%, #004E92 100%) 0% 0% no-repeat padding-box; */
		/* background-color: #15101E; */
		background-image: null;
		background-size: cover;
		background-repeat: no-repeat;
		background-position: center;
		-webkit-box-orient: vertical;
		-webkit-box-direction: normal;
		-webkit-flex-direction: column;
		-webkit-box-align: center;
		-webkit-align-items: center;
		-webkit-box-pack: center;
		-webkit-justify-content: center;
	}
    .css-5nata0,
	[data-css-5nata0] {
		width: 100%;
		height: 100%;
		display: -webkit-box;
		display: -moz-box;
		display: -ms-flexbox;
		display: -webkit-flex;
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		/* padding: 130px 0 44px; */
		position: relative;
		overflow: hidden;
		-webkit-box-orient: vertical;
		-webkit-box-direction: normal;
		-webkit-flex-direction: column;
		-webkit-box-align: center;
		-webkit-align-items: center;
		-webkit-box-pack: center;
		-webkit-justify-content: center;
	}

	.css-5nata0 .upper-section,
	[data-css-5nata0] .upper-section {
		display: -webkit-box;
		display: -moz-box;
		display: -ms-flexbox;
		display: -webkit-flex;
		display: flex;
		flex-wrap: wrap;
		flex-direction: row;
		justify-content: center;
		align-items: center;
		width: 100%;
		margin-bottom: 52px;
		-webkit-box-lines: multiple;
		-webkit-flex-wrap: wrap;
		-webkit-box-orient: horizontal;
		-webkit-box-direction: normal;
		-webkit-flex-direction: row;
		-webkit-box-pack: center;
		-webkit-justify-content: center;
		-webkit-box-align: center;
		-webkit-align-items: center;
	}
	
	.css-5nata0 .upper-section .content,
	[data-css-5nata0] .upper-section .content {
		position: absolute;
		left: 100px;
	}
	
	.css-5nata0 .upper-section .content .title,
	[data-css-5nata0] .upper-section .content .title {
		font-size: 48px;
		font-weight: 800;
		letter-spacing: 0.25px;
		max-width: 856px;
		margin-bottom: 20px;
	}
	
	.css-5nata0 .upper-section .content .sub-title,
	[data-css-5nata0] .upper-section .content .sub-title {
		font-size: 18px;
		line-height: 25px;
		font-weight: 500;
		letter-spacing: 0.19px;
		max-width: 856px;
	}
	
    .css-5nata0 .upper-section .content .apps,[data-css-5nata0] .upper-section .content .apps {
                margin: 26px 0 20px;
                padding: 8px 30px;
                font-size: 16px;
                line-height: 21px;
                background-color: transparent;
                border: 2px solid #FFFFFF;
                border-radius: 20px;
                align-self: flex-start;
                -webkit-align-self: flex-start;
            }

            .css-5nata0 .upper-section .devices-preview,[data-css-5nata0] .upper-section .devices-preview {
                position: relative;
                right: -35%;
                width: 100%;
                max-width: 1200px;
            }

            .css-5nata0 .upper-section .devices-preview img,[data-css-5nata0] .upper-section .devices-preview img {
                width: 100%;
            }

            .css-5nata0 .support-platforms img,[data-css-5nata0] .support-platforms img {
                width: 100%;
                height: auto;
                padding: 0 36px;
            }

            @media(max-width: 1100px) {
                .css-5nata0,[data-css-5nata0] {
                    padding:100px 0;
                }

                .css-5nata0 .upper-section .content,[data-css-5nata0] .upper-section .content {
                    position: unset;
                    margin-bottom: 60px;
                    display: -webkit-box;
                    display: -moz-box;
                    display: -ms-flexbox;
                    display: -webkit-flex;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    text-align: center;
                    -webkit-box-align: center;
                    -webkit-align-items: center;
                }

                .css-5nata0 .upper-section .content .title br,[data-css-5nata0] .upper-section .content .title br {
                    display: none;
                }

                .css-5nata0 .upper-section .content .apps,[data-css-5nata0] .upper-section .content .apps {
                    align-self: center;
                    -webkit-align-self: center;
                }

                .css-5nata0 .upper-section .devices-preview,[data-css-5nata0] .upper-section .devices-preview {
                    right: 0;
                }
            }

            @media(max-width: 768px) {
                .css-5nata0 .upper-section .content .title,[data-css-5nata0] .upper-section .content .title {
                    font-size:32px;
                    letter-spacing: 0.34px;
                }

                .css-5nata0 .upper-section .content .sub-title,[data-css-5nata0] .upper-section .content .sub-title {
                    font-size: 16px;
                    letter-spacing: 0.17px;
                }

                .css-5nata0 .upper-section .content .apps,[data-css-5nata0] .upper-section .content .apps {
                    align-self: center;
                    -webkit-align-self: center;
                }
            }

            @media(max-width: 425px) {
                .css-5nata0 .support-platforms img,[data-css-5nata0] .support-platforms img {
                    padding:0 10px;
                }
            }

/* for genre channel items*/

.item1{
    height: 80px;
    width: 80px;
    
    /* background-color:#fff; */
}

.grid-container > div {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  /* justify-content: center;
  align-items: center; */
  font-size: 2em;
  /* color: #20263f; */
  border-radius: 15px;
      background-color:  #4D5659; 
      text-align: center;
      padding: 10px 0;
}

.grid-container {
/* width: 100%; */
  display: -ms-grid;
  display: grid;
  grid-gap: 40px;
  grid-template-columns: repeat(auto-fit, minmax(50px, 1fr));
  grid-auto-rows: 50px;
  justify-items: center;
  
  -webkit-box-pack: space-evenly;
  
      -ms-flex-pack: space-evenly;
  
          justify-content: space-evenly;
  padding: 10px;
    }
    .grid-container > div.item1 > img {
  width: 100%;
  height: 100%;
  -o-object-fit: contain;
     object-fit: contain;
}

/*devices circle*/

.flex-container {
        width: 100%;   
    
  padding: 0;
  margin: 0;
  list-style: none;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -ms-flex-wrap: wrap;
      flex-wrap: wrap;
}

/* Responsive layout - makes a one column-layout instead of two-column layout */
@media (max-width: 800px) {
    
    .flex-container>div {
    text-align: center ;
    -webkit-box-flex: 0;
        -ms-flex: 0 50%;
            flex: 0 50%;
  }
  .flex-container>div:not(:nth-last-child(-n+2))  {
    /* background-color: red; */
    padding-bottom: 5%;
}
}

.space-evenly { 
  -webkit-box-pack: space-evenly; 
      -ms-flex-pack: space-evenly; 
          justify-content: space-evenly; 
}
    .hovicon {
        display: inline-block;
        font-size: 45px;
        line-height: 90px;
        cursor: pointer;
        margin: 20px;
        width: 90px;
        height: 90px;
        border-radius: 50%;
        text-align: center;
        position: relative;
        text-decoration: none;
        z-index: 1;
        color: #fff;
    }
    
.hovicon:after {
    pointer-events: none;
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    content:'';
    -webkit-box-sizing: content-box;
    -moz-box-sizing: content-box;
    box-sizing: content-box;
}
.hovicon:before {
    speak: none;
    font-size: 48px;
    line-height: 90px;
    font-style: normal;
    font-weight: normal;
    font-variant: normal;
    text-transform: none;
    display: block;
    -webkit-font-smoothing: antialiased;
}
/* Effect 1 */
.hovicon.effect-1 {
    background: rgba(255, 255, 255, 0.1);
    -webkit-transition: background 0.2s, color 0.2s;
    -moz-transition: background 0.2s, color 0.2s;
    transition: background 0.2s, color 0.2s;
}
.hovicon.effect-1:after {
    top: -7px;
    left: -7px;
    padding: 7px;
    box-shadow: 0 0 0 4px #FF712F;
    -webkit-transition: -webkit-transform 0.2s, opacity 0.2s;
    -webkit-transform: scale(1);
    -moz-transition: -moz-transform 0.2s, opacity 0.2s;
    -moz-transform: scale(1);
    -ms-transform: scale(1);
    transition: transform 0.2s, opacity 0.2s;
    transform: scale(1);
    opacity: 0;
    
}
/* Effect 1a */
 .hovicon.effect-1.sub-a:hover {
    background: #000428;
    
}
.hovicon.effect-1.sub-a:hover i {
    
}
.hovicon.effect-1.sub-a:hover:after {
    -webkit-transform: scale(1);
    -moz-transform: scale(1);
    -ms-transform: scale(1);
    transform: scale(1);
    opacity: 1;
}

circle {
  fill: white;
  fill-opacity: 0;
  stroke: #FF712F;
  stroke-width: 2px;
  transition: stroke-opacity 0.8s, stroke-width 0.8s, transform 0.8s;
  transform-origin: 50% 50%;
  stroke-opacity: 0;
}

circle:hover {
  stroke-opacity: 1;
  stroke-width: 0.5;
  transform: scale(15);
}

.circle
{ width:140px;
    height:140px;
    border-radius:70px;
    font-size:20px;
    color:#fff;
    line-height:100px;
    text-align:center;
    background:#000;
    }
    #roku{

      background-image: url("../frontend/img/newicon/roku.png");

/*background-size: cover;*/
background-size: 87px;
background-repeat: no-repeat;
background-position: center;
    }
    #amazonfiretv{

      background-image: url("../frontend/img/newicon/andriodtv.png");

/*background-size: cover;*/
background-size: 100px;
background-repeat: no-repeat;
background-position: center;
    }
    #appletv{

        background-image: url("../frontend/img/newicon/appletv.png");

/*background-size: cover;*/
background-size: 60px;
background-repeat: no-repeat;
background-position: center;
    }
    #iostv{

      background-image: url("../frontend/images/s/phones-and-tablets/apple_logo_360.png");

/*background-size: cover;*/
background-size: 50px;
background-repeat: no-repeat;
background-position: center;
    } 
     #android{

      background-image: url("../frontend/img/newicon/android.png");

/*background-size: cover;*/
background-size: 87px;
background-repeat: no-repeat;
background-position: center;
    }
    #samsungtv{

      background-image: url("../frontend/img/newicon/smarttv.png");

/*background-size: cover;*/
background-size: 87px;
background-repeat: no-repeat;
background-position: center;
    }

    .circle:hover .innerCircle{
      stroke-opacity: 1;
  stroke-width: 0.5;
  transform: scale(15);
    }
	nav#mainNav {
    background-color: #004E92!important;
}
    </style>
@endsection

<section class="page-section " id="portfolio" style="padding: 7.5rem 0;">

    <div class="css-thjkr7">
        <div class="css-5nata0">
            <div class="upper-section">
                <div class="content">
                    <div class="title">Download the free Streamly <br>apps for all your devices</div>
                    <div class="sub-title">1. Roku
                        <br> 2. FireTV <br> 3. IOS <br> 4. IOS TV <br> 5. Android <br> 6. Samsung</div>
                    <!-- <div id="scrollButton" class="btn  btn-see-all-plan btn-xl" style=" text-transform: uppercase;">See all apps and devices</div> -->
                </div>
                
                <div class="devices-preview"><img class="lazy img-fluid" data-src="{{url('../frontend/img/devices3.png')}}" 
  
alt="image of tv guide"
></div>
            </div>
        </div>
        <div class="text-center" style="margin: 2vw;">

            <h2 class="section-heading">GET IT ON</h2>
            </div>
        
        <div class="flex-container space-evenly" style="margin-left: 100px; margin-right: 100px;">
            
            <div><a href="https://channelstore.roku.com/details/808032303f904844be7cb88148cc0aad/streamlytv" target="_blank"><svg class = "circle" id="roku" expanded = "true" height = "200px" width = "200px" >
            <circle class = "innerCircle" cx = "50%" cy = "50%" r = "5px">
            </svg></a></div>
              
              <div><a href="https://www.amazon.in/Adaptive-Media-Inc-StreamlyTV/dp/B09VH3K4WH/ref=sr_1_1?keywords=streamlyTv&qid=1657795043&s=mobile-apps&sr=1-1" target="_blank"><svg class = "circle" id="amazonfiretv" expanded = "true" height = "200px" width = "200px" >
              <circle class = "innerCircle" cx = "50%" cy = "50%" r = "5px">
              </svg></a></div>

              <div><a href="https://apps.apple.com/us/app/streamlytv/id1607812200" target="_blank"><svg class = "circle" id="appletv" expanded = "true" height = "200px" width = "200px" >
              <circle class = "innerCircle" cx = "50%" cy = "50%" r = "5px">
              </svg></a></div>

              <div><svg class = "circle" id="iostv" expanded = "true" height = "200px" width = "200px" >
              <circle class = "innerCircle" cx = "50%" cy = "50%" r = "5px">
              </svg></div>
              
              <div><a href="https://play.google.com/store/apps/details?id=com.adaptivemedia.streamlytv&hl=en&gl=US" target="_blank"><svg class = "circle" id="android" expanded = "true" height = "200px" width = "200px" >
              <circle class = "innerCircle" cx = "50%" cy = "50%" r = "5px">
              </svg></a></div>

              <div><svg class = "circle" id="samsungtv" expanded = "true" height = "200px" width = "200px" >
              <circle class = "innerCircle" cx = "50%" cy = "50%" r = "5px">
              </svg></div> </div>
    </div>
    </section>