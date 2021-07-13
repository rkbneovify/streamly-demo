<?php
use Laravel\Cashier\Cashier; ?>
@extends('frontend/partials/master')

@section('stylesheets-bottom')
	<style>
		nav#mainNav {
			background-color: #004E92;
		}

		body{
			background: #F9F9F9 0% 0% no-repeat padding-box;
		}
		
		.page-section {
		    padding: 8rem 0;
		}

		ul.setting-card {
		    margin: 0 auto;
		    list-style: none;
		    box-shadow: 0px 3px 6px #00000029;
    		padding: 0;
		}

		ul.setting-card li:first-child {
			font-weight: bold;
			background: #004E92 0% 0% no-repeat padding-box;
			padding: 12px 34px;
			letter-spacing: 0.24px;
			color: #FFFFFF;
			border-top-right-radius: 6px;
    		border-top-left-radius: 6px;
		}

		ul.setting-card {
		    margin: 0 auto;
		    color: #5E5E5E;
		}	

		.col-centered {
		    margin: 0 auto;
		    position: relative;
		}

		ul.setting-card li:not(:first-child) {
		    padding: 12px 34px;
		    letter-spacing: 0.24px;
		}	

		.light-brown{
			color: #989898;
		}

		.account-save-btn {
			background: #C4C4C4 0% 0% no-repeat padding-box;
			border-radius: 6px;
		}

		.account-cancel-btn {
			border: 1px solid #707070;
			border-radius: 6px;
		}

		.cancel-subscription {
			display: block;
    		text-align: right;
    		color: #CF0000;
		}

		i.fa.fa-trash {
		    cursor: pointer;
		}

		i.fa.fa-edit {
			cursor: pointer;
		}

		.modal-dialog {
			min-height: calc(100vh - 60px);
		    display: flex;
		    flex-direction: column;
		    justify-content: center;
		    overflow: auto;
		}

		.modal-body {
		    position: relative;
		    flex: 1 1 auto;
		    padding: 3rem 5rem;
		}

		.modal-body p{
			letter-spacing: 0.24px;
			color: #5E5E5E;
		}
		.bd-highlight {
    background-color: rgba(86,61,124,.15);
    border: 1px solid rgba(86,61,124,.15);
}
/* device management */
.device-body1 {
 position:relative;
}
.device-body1 .qY3zp {
 transition:transform .4s,height .4s .3s,margin .4s .3s;
}
.device-body1 .yUCqnc {
 margin:0;
 opacity:0;
 transform:scale(0);
 transform-origin:left top;
 transition:transform .4s,opacity .4s,height .4s .3s,margin .4s .3s;
}
.device-body1 .So5Ulf {
 opacity:0;
 transform:scale(0);
}
.device-body1 .SQRTY {
 transform-origin:left top;
 transition:transform .4s .1s,opacity .4s .1s,height .4s .3s,margin .4s .3s;
}
.device-body1 .TKwz0e {
 height:0;
 margin:0;
}
.device-body {
 display:-webkit-box;
 display:-moz-box;
 display:-webkit-flex;
 display:-ms-flexbox;
 display:flex;
 -moz-flex-wrap:wrap;
 flex-wrap:wrap;
}
.jpoelc {
 height:100%;
}

.device-box-card-inside-classb {
 height:100%;
 letter-spacing:.01428571em;
 font-family:Roboto,Arial,sans-serif;
 font-size:.875rem;
 font-weight:400;
 line-height:1.25rem;
}
.device-box-card-inside {
 display:-webkit-box;
 display:-moz-box;
 display:-webkit-flex;
 display:-ms-flexbox;
 display:flex;
 flex-direction:column;
 box-sizing:border-box;
 position:relative;
 outline:none;
 color:inherit;
 text-decoration:none;
 cursor:pointer;
 overflow:hidden;
}
.device-box-card-inside:first-child {
 border-top-left-radius:inherit;
 border-top-right-radius:inherit;
}
.device-box-card-inside:last-child {
 border-bottom-left-radius:inherit;
 border-bottom-right-radius:inherit;
}
.device-box-card-inside {
 --mdc-ripple-fg-size:0;
 --mdc-ripple-left:0;
 --mdc-ripple-top:0;
 --mdc-ripple-fg-scale:1;
 --mdc-ripple-fg-translate-end:0;
 --mdc-ripple-fg-translate-start:0;
 will-change:transform,opacity;
}
.device-box-card-inside::before {
 position:absolute;
 -moz-border-radius:50%;
 border-radius:50%;
 opacity:0;
 pointer-events:none;
 content:"";
}
.device-box-card-inside::after {
 position:absolute;
 -moz-border-radius:50%;
 border-radius:50%;
 opacity:0;
 pointer-events:none;
 content:"";
}
.device-box-card-inside::before {
 transition:opacity 15ms linear,background-color 15ms linear;
 z-index:1;
 z-index:var(--mdc-ripple-z-index,1);
}
.device-box-card-inside::after {
 z-index:0;
 z-index:var(--mdc-ripple-z-index,0);
}
.device-box-card-inside.VfPpkd-ksKsZd-mWPk3d::before {
 transform:scale(var(--mdc-ripple-fg-scale,1));
}
.device-box-card-inside.VfPpkd-ksKsZd-mWPk3d::after {
 top:0;
 left:0;
 transform:scale(0);
 transform-origin:center center;
}
.device-box-card-inside.VfPpkd-ksKsZd-mWPk3d-OWXEXe-ZNMTqd::after {
 top:var(--mdc-ripple-top,0);
 left:var(--mdc-ripple-left,0);
}
.device-box-card-inside.VfPpkd-ksKsZd-mWPk3d-OWXEXe-Tv8l5d-lJfZMc::after {
 animation:mdc-ripple-fg-radius-in 225ms forwards,mdc-ripple-fg-opacity-in 75ms forwards;
}
.device-box-card-inside.VfPpkd-ksKsZd-mWPk3d-OWXEXe-Tv8l5d-OmS1vf::after {
 animation:mdc-ripple-fg-opacity-out 150ms;
 transform:translate(var(--mdc-ripple-fg-translate-end,0)) scale(var(--mdc-ripple-fg-scale,1));
}
.device-box-card-inside::before {
 top:-moz-calc(50% - 100%);
 top:calc(50% - 100%);
 left:-moz-calc(50% - 100%);
 left:calc(50% - 100%);
 width:200%;
 height:200%;
}
.device-box-card-inside::after {
 top:-moz-calc(50% - 100%);
 top:calc(50% - 100%);
 left:-moz-calc(50% - 100%);
 left:calc(50% - 100%);
 width:200%;
 height:200%;
}
.device-box-card-inside.VfPpkd-ksKsZd-mWPk3d::after {
 width:var(--mdc-ripple-fg-size,100%);
 height:var(--mdc-ripple-fg-size,100%);
}
.device-box-card-inside::before {
 background-color:#000;
 background-color:var(--mdc-ripple-color,#000);
}
.device-box-card-inside::after {
 background-color:#000;
 background-color:var(--mdc-ripple-color,#000);
}
.device-box-card-inside:hover::before {
 opacity:.04;
 opacity:var(--mdc-ripple-hover-opacity,0.04);
}
.device-box-card-inside.VfPpkd-ksKsZd-mWPk3d-OWXEXe-AHe6Kc-XpnDCe::before {
 transition-duration:75ms;
 opacity:.12;
 opacity:var(--mdc-ripple-focus-opacity,0.12);
}
.device-box-card-inside:not(.VfPpkd-ksKsZd-mWPk3d):focus::before {
 transition-duration:75ms;
 opacity:.12;
 opacity:var(--mdc-ripple-focus-opacity,0.12);
}
.device-box-card-inside:not(.VfPpkd-ksKsZd-mWPk3d)::after {
 transition:opacity 150ms linear;
}
.device-box-card-inside:not(.VfPpkd-ksKsZd-mWPk3d):active::after {
 transition-duration:75ms;
 opacity:.12;
 opacity:var(--mdc-ripple-press-opacity,0.12);
}
.device-box-card-inside.VfPpkd-ksKsZd-mWPk3d {
 --mdc-ripple-fg-opacity:var(--mdc-ripple-press-opacity,0.12);
}
body:not(.uOat3d) .device-box-card-inside:not(.VfPpkd-ksKsZd-mWPk3d).device-box-card-inside-classb:focus:not(:hover)::before {
 opacity:0;
}
.TDjQOc {
 position:absolute;
 right:0;
 top:0;
}
.TDjQOc2 {
 position:absolute;
 left:0;
 top:0;
}
.jpoelc {
 position:relative;
}
.dotmenu1 {
 -moz-user-select:none;
 -moz-transition:background .3s;
 transition:background .3s;
 border:0;
 -moz-border-radius:50%;
 border-radius:50%;
 color:#444;
 cursor:pointer;
 display:inline-block;
 fill:#444;
 flex-shrink:0;
 height:48px;
 outline:none;
 overflow:hidden;
 position:relative;
 text-align:center;
 width:48px;
 z-index:0;
}
.dotmenu1.RDPZE {
 cursor:default;
}
.NWlf3e {
 -moz-transform:translate(-50%,-50%) scale(0);
 transform:translate(-50%,-50%) scale(0);
 -moz-transition:opacity .2s ease;
 transition:opacity .2s ease;
 background-size:cover;
 left:0;
 opacity:0;
 pointer-events:none;
 position:absolute;
 top:0;
 visibility:hidden;
}
.dotmenu1.iWO5td>.NWlf3e {
 transition:-webkit-transform .3s cubic-bezier(0.0,0.0,0.2,1);
 transition:transform .3s cubic-bezier(0.0,0.0,0.2,1);
 -moz-transform:translate(-50%,-50%) scale(2.2);
 transform:translate(-50%,-50%) scale(2.2);
 opacity:1;
 visibility:visible;
}
.dotmenu1.j7nIZb>.NWlf3e {
 -moz-transform:translate(-50%,-50%) scale(2.2);
 transform:translate(-50%,-50%) scale(2.2);
 visibility:visible;
}
.dotmenu2.u3bW4e {
 background-color:rgba(153,153,153,0.4);
}
.dotmenu2.iWO5td>.NWlf3e {
 background-image:radial-gradient(circle farthest-side,rgba(153,153,153,0.4),rgba(153,153,153,0.4) 80%,rgba(153,153,153,0) 100%);
}
.ZDSs1.iWO5td>.NWlf3e {
 background-image:radial-gradient(circle farthest-side,rgba(204,204,204,0.251),rgba(204,204,204,0.251) 80%,rgba(204,204,204,0) 100%);
}
.dotmenu2.RDPZE {
 color:rgba(68,68,68,0.502);
 fill:rgba(68,68,68,0.502);
}
.dotmenu.u3bW4e {
 outline:1px solid transparent;
}
.device-details {
 align-items:center;
 display:-webkit-box;
 display:-moz-box;
 display:-webkit-flex;
 display:-ms-flexbox;
 display:flex;
 flex-shrink:0;
}
.device-name {
 letter-spacing:.0125em;
 font-family:Roboto,Arial,sans-serif;
 font-size:1rem;
 font-weight:500;
 line-height:1.5rem;
 margin-top:-4px;
}
.device-location {
 color:#5f6368;
}
.device-detail-txt {
 min-height:84px;
}
.midline {
 background:#dadce0;
 flex-shrink:0;
 height:1px;
 width:100%;
}
.IfKkJe {
 color:#5f6368;
}
.device-more-details:not(:empty) {
 display:block;
 margin-top:8px;
}
.device-logo {
 display:block;
 margin:auto;
}
.device-logo-div {
 background:no-repeat url(https://www.gstatic.com/identity/boq/accountsettingssecuritycommon/images/sprites/devices_realistic_72-b9bd1ca60228ef0457a37afb84e72bdd.png) 0 -73px;
 background-size:72px 1532px;
 width:72px;
 height:72px;
}
.device-logo-div[data-form-factor='1'] {
 background:no-repeat url(https://www.gstatic.com/identity/boq/accountsettingssecuritycommon/images/sprites/devices_realistic_72-b9bd1ca60228ef0457a37afb84e72bdd.png) 0 -584px;
 background-size:72px 1532px;
 width:72px;
 height:72px;
}
.device-logo-div[data-form-factor='2'] {
 background:no-repeat url(https://www.gstatic.com/identity/boq/accountsettingssecuritycommon/images/sprites/devices_realistic_72-b9bd1ca60228ef0457a37afb84e72bdd.png) 0 -1314px;
 background-size:72px 1532px;
 width:72px;
 height:72px;
}
.device-logo-div[data-form-factor='3'] {
 background:no-repeat url(https://www.gstatic.com/identity/boq/accountsettingssecuritycommon/images/sprites/devices_realistic_72-b9bd1ca60228ef0457a37afb84e72bdd.png) 0 -1095px;
 background-size:72px 1532px;
 width:72px;
 height:72px;
}
.device-logo-div[data-os='1'][data-form-factor='1'] {
 background:no-repeat url(https://www.gstatic.com/identity/boq/accountsettingssecuritycommon/images/sprites/devices_realistic_72-b9bd1ca60228ef0457a37afb84e72bdd.png) 0 -1460px;
 background-size:72px 1532px;
 width:72px;
 height:72px;
}
.device-logo-div[data-os='2'][data-form-factor='1'] {
 background:no-repeat url(https://www.gstatic.com/identity/boq/accountsettingssecuritycommon/images/sprites/devices_realistic_72-b9bd1ca60228ef0457a37afb84e72bdd.png) 0 -511px;
 background-size:72px 1532px;
 width:72px;
 height:72px;
}
.device-logo-div[data-os='3'][data-form-factor='1'] {
 background:no-repeat url(https://www.gstatic.com/identity/boq/accountsettingssecuritycommon/images/sprites/devices_realistic_72-b9bd1ca60228ef0457a37afb84e72bdd.png) 0 -1168px;
 background-size:72px 1532px;
 width:72px;
 height:72px;
}
.device-logo-div[data-os='4'][data-form-factor='2'] {
 background:no-repeat url(https://www.gstatic.com/identity/boq/accountsettingssecuritycommon/images/sprites/devices_realistic_72-b9bd1ca60228ef0457a37afb84e72bdd.png) 0 -657px;
 background-size:72px 1532px;
 width:72px;
 height:72px;
}
.device-logo-div[data-os='4'][data-form-factor='3'] {
 background:no-repeat url(https://www.gstatic.com/identity/boq/accountsettingssecuritycommon/images/sprites/devices_realistic_72-b9bd1ca60228ef0457a37afb84e72bdd.png) 0 -1387px;
 background-size:72px 1532px;
 width:72px;
 height:72px;
}
.device-logo-div[data-os='5'][data-form-factor='2'] {
 background:no-repeat url(https://www.gstatic.com/identity/boq/accountsettingssecuritycommon/images/sprites/devices_realistic_72-b9bd1ca60228ef0457a37afb84e72bdd.png) 0 0;
 background-size:72px 1532px;
 width:72px;
 height:72px;
}
.device-logo-div[data-os='5'][data-form-factor='3'] {
 background:no-repeat url(https://www.gstatic.com/identity/boq/accountsettingssecuritycommon/images/sprites/devices_realistic_72-b9bd1ca60228ef0457a37afb84e72bdd.png) 0 -949px;
 background-size:72px 1532px;
 width:72px;
 height:72px;
}
.device-logo-div[data-os='6'] {
 background:no-repeat url(https://www.gstatic.com/identity/boq/accountsettingssecuritycommon/images/sprites/devices_realistic_72-b9bd1ca60228ef0457a37afb84e72bdd.png) 0 -1241px;
 background-size:72px 1532px;
 width:72px;
 height:72px;
}
.device-logo-div[data-form-factor='4'] {
 background:no-repeat url(https://www.gstatic.com/identity/boq/accountsettingssecuritycommon/images/sprites/devices_realistic_72-b9bd1ca60228ef0457a37afb84e72bdd.png) 0 -438px;
 background-size:72px 1532px;
 width:72px;
 height:72px;
}
.device-logo-div[data-form-factor='5'] {
 background:no-repeat url(https://www.gstatic.com/identity/boq/accountsettingssecuritycommon/images/sprites/devices_realistic_72-b9bd1ca60228ef0457a37afb84e72bdd.png) 0 -219px;
 background-size:72px 1532px;
 width:72px;
 height:72px;
}
.device-logo-div[data-form-factor='6'],
.device-logo-div[data-form-factor='8'] {
 background:no-repeat url(https://www.gstatic.com/identity/boq/accountsettingssecuritycommon/images/sprites/devices_realistic_72-b9bd1ca60228ef0457a37afb84e72bdd.png) 0 -876px;
 background-size:72px 1532px;
 width:72px;
 height:72px;
}
.device-logo-div[data-form-factor='7'] {
 background:no-repeat url(https://www.gstatic.com/identity/boq/accountsettingssecuritycommon/images/sprites/devices_realistic_72-b9bd1ca60228ef0457a37afb84e72bdd.png) 0 -1022px;
 background-size:72px 1532px;
 width:72px;
 height:72px;
}
.device-logo-div[data-form-factor='9'] {
 background:no-repeat url(https://www.gstatic.com/identity/boq/accountsettingssecuritycommon/images/sprites/devices_realistic_72-b9bd1ca60228ef0457a37afb84e72bdd.png) 0 -146px;
 background-size:72px 1532px;
 width:72px;
 height:72px;
}
.device-logo-div[data-form-factor='10'] {

 background-color: #575B60;
 background-image: url("../frontend/img/newicon/roku.png"); 

/*background-size: cover;*/
background-size: 70px;
background-repeat: no-repeat;
background-position: center;
}
.device-logo-div[data-form-factor='11'] {

 background-color: #575B60;
 background-image: url("../frontend/img/newicon/appletv.png"); 

/*background-size: cover;*/
background-size: 70px;
background-repeat: no-repeat;
background-position: center;
}
.device-logo-div[data-form-factor='12'] {

 background-color: #575B60;
 background-image: url("../frontend/img/newicon/andriodtv.png"); 

/*background-size: cover;*/
background-size: 70px;
background-repeat: no-repeat;
background-position: center;
}
.device-logo-div[data-form-factor='13'] {

 background-color: #575B60;
 background-image: url("../frontend/img/newicon/smarttv.png"); 

/*background-size: cover;*/
background-size: 70px;
background-repeat: no-repeat;
background-position: center;
}
.device-name-div {
 margin-bottom:8px;
 word-break:break-word;
}
.K45rif {
 align-items:center;
 display:-webkit-box;
 display:-moz-box;
 display:-webkit-flex;
 display:-ms-flexbox;
 display:flex;
}
.K45rif .kwfIKc {
 flex-shrink:0;
 margin-right:4px;
}
.K45rif .mMF6qc {
 fill:#1a73e8;
}
.K45rif .bhQaRb {
 fill:#ea4335;
}
@media (min-width:888px) {
 .F4S0Db {
  margin-bottom:24px;
  margin-right:24px;
 }
 .device-body {
  margin-bottom:-24px;
 }
 .device-details,
 .device-detail-txt {
  padding:24px;
 }
}
@media (max-width:887px) and (min-width:720px) {
 .F4S0Db {
  margin-bottom:24px;
  margin-right:24px;
 }
 .device-body {
  margin-bottom:-24px;
 }
 .device-details,
 .device-detail-txt {
  padding:16px;
 }
}
@media (max-width:719px) and (min-width:480px) {
 .F4S0Db {
  margin-bottom:16px;
 }
 .device-body {
  margin-bottom:-16px;
 }
 .device-details,
 .device-detail-txt {
  padding:16px;
 }
}
@media (max-width:479px) {
 .F4S0Db {
  margin-bottom:8px;
 }
 .device-body {
  margin-bottom:-8px;
 }
 .device-details,
 .device-detail-txt {
  padding:16px;
 }
}

@media (min-width:888px) {
 .F4S0Db {
  width:-moz-calc(33.3% - 16px);
  width:calc(33.3% - 16px);
 }
 .F4S0Db:nth-of-type(3n) {
  margin-right:0;
 }
}
@media (max-width:887px) and (min-width:720px) {
 .F4S0Db {
  width:-moz-calc(50% - 12px);
  width:calc(50% - 12px);
 }
 .F4S0Db:nth-of-type(2n) {
  margin-right:0;
 }
}
@media (max-width:719px) {
 .F4S0Db {
  margin-right:0;
  width:100%;
 }
 .F4S0Db .device-box-card-inside-classb {
  flex-direction:row;
 }
 .jTztaf {
  left:6px;
  top:6px;
 }
 .device-name {
  margin-right:24px;
 }
 .midline {
  height:100%;
  width:1px;
 }
 .device-more-details:not(:empty) {
  margin-top:4px;
 }
}
.device-box-card {
 -moz-border-radius:4px;
 border-radius:4px;
 -moz-border-radius:var(--mdc-shape-medium,4px);
 border-radius:var(--mdc-shape-medium,4px);
 background-color:#fff;
 background-color:var(--mdc-theme-surface,#fff);
 position:relative;
 -moz-box-shadow:0 2px 1px -1px rgba(0,0,0,0.2),0 1px 1px 0 rgba(0,0,0,0.14),0 1px 3px 0 rgba(0,0,0,0.12);
 box-shadow:0 2px 1px -1px rgba(0,0,0,0.2),0 1px 1px 0 rgba(0,0,0,0.14),0 1px 3px 0 rgba(0,0,0,0.12);
 display:-webkit-box;
 display:-moz-box;
 display:-webkit-flex;
 display:-ms-flexbox;
 display:flex;
 flex-direction:column;
 box-sizing:border-box;
}
.VfPpkd-BFbNVe-bF1uUb {
 position:absolute;
 -moz-border-radius:inherit;
 border-radius:inherit;
 pointer-events:none;
 opacity:0;
 opacity:var(--mdc-elevation-overlay-opacity,0);
 transition:opacity 280ms cubic-bezier(0.4,0,0.2,1);
 background-color:#fff;
 background-color:var(--mdc-elevation-overlay-color,#fff)
}
.device-box-card .VfPpkd-BFbNVe-bF1uUb {
 width:100%;
 height:100%;
 top:0;
 left:0;
}
.device-box-card::after {
 -moz-border-radius:4px;
 border-radius:4px;
 -moz-border-radius:var(--mdc-shape-medium,4px);
 border-radius:var(--mdc-shape-medium,4px);
 position:absolute;
 box-sizing:border-box;
 width:100%;
 height:100%;
 top:0;
 left:0;
 border:1px solid transparent;
 -moz-border-radius:inherit;
 border-radius:inherit;
 content:"";
 pointer-events:none;
}
.device-box-card-classb {
 -moz-box-shadow:0 0 0 0 rgba(0,0,0,0.2),0 0 0 0 rgba(0,0,0,0.14),0 0 0 0 rgba(0,0,0,0.12);
 box-shadow:0 0 0 0 rgba(0,0,0,0.2),0 0 0 0 rgba(0,0,0,0.14),0 0 0 0 rgba(0,0,0,0.12);
 border-width:1px;
 border-style:solid;
 border-color:#e0e0e0;
}
.device-box-card-classb::after {
 border:none;
}
.KC1dQ {
 -moz-border-radius:8px;
 border-radius:8px;
 background-color:#fff;
 border-width:0;
 -moz-box-shadow:0 1px 2px 0 rgba(60,64,67,0.3),0 1px 3px 1px rgba(60,64,67,0.15);
 box-shadow:0 1px 2px 0 rgba(60,64,67,0.3),0 1px 3px 1px rgba(60,64,67,0.15);
}
.Usd1Ac {
 -moz-border-radius:8px;
 border-radius:8px;
 background-color:#fff;
 border:1px solid #dadce0;
 -moz-box-shadow:none;
 box-shadow:none;
}
.Usd1Ac .VfPpkd-BFbNVe-bF1uUb {
 opacity:0;
}
.KC1dQ .VfPpkd-BFbNVe-bF1uUb {
 opacity:0;
}
.tcY7Yc.KC1dQ .BNK4qe {
 overflow:hidden;
 padding:24px 40px;
}
.tcY7Yc.KC1dQ .Cz41Yb {
 padding:0 24px 24px;
}

@media (min-width:550px) {
 .tcY7Yc {
  border:1px solid #dadce0;
  margin-top:24px;
 }
 .tcY7Yc.KC1dQ .Cz41Yb {
  padding-top:24px;
 }
}
@media (max-width:479px) {
 .C6axcb {
  padding:16px 24px;
  border-top-left-radius:initial;
  border-top-right-radius:initial;
 }
 .tcY7Yc.KC1dQ {
  -moz-border-radius:0;
  border-radius:0;
 }
 .tcY7Yc.KC1dQ .Cz41Yb {
  padding:8px 24px 24px;
 }
 .tcY7Yc.KC1dQ .BNK4qe {
  padding:16px 24px;
 }
}
.MhXXcc {
 line-height:44px;
 position:relative;
}
.NZp2ef {
 background-color:#e8eaed;
}

/*browser used */
.eIXuRc+.eIXuRc.EGriic {
	border-top: 1px solid #dadce0;
	margin-right: -24px;
	margin-top: 24px
}

.eIXuRc {
	padding-top: 24px
}

.NQ9Zpd {
	letter-spacing: .07272727em;
	font-family: Roboto, Arial, sans-serif;
	font-size: .6875rem;
	font-weight: 500;
	line-height: 1rem;
	text-transform: uppercase;
	color: #5f6368;
	padding-bottom: 16px
}

.YrUtyd {
	align-items: flex-start;
	display: flex;
	flex-wrap: wrap
}


.YrUtyd+.FgENu {
	padding-top: 12px
}

.FgENu+.YrUtyd {
	padding-top: 24px
}
.Qc8ulc {
	align-items: center;
	display: -webkit-inline-box;
	display: inline-flex;
	padding-bottom: 12px;
	width: 100%
}

.Qc8ulc:last-child {
	padding-bottom: 0
}

@media (min-width:720px) {
	.Qc8ulc {
		width: 50%
	}
	.Qc8ulc:nth-last-child(2) {
		padding-bottom: 0
	}
}


.CCq0jd {
	padding-right: 16px;
	width: 20px
}

.CCq0jd[data-nest-device="true"] {
	height: 32px;
	width: 32px
}

.aiicHc {
	background: no-repeat url(https://www.gstatic.com/identity/boq/accountsettingssecuritycommon/images/sprites/browsers-7aaa748b21991550f4cff35726a2e195.png) 0 -147px;
	background-size: 20px 167px;
	width: 20px;
	height: 20px
}

.aiicHc[data-browser='1'] {
	background: no-repeat url(https://www.gstatic.com/identity/boq/accountsettingssecuritycommon/images/sprites/browsers-7aaa748b21991550f4cff35726a2e195.png) 0 -42px;
	background-size: 20px 167px;
	width: 20px;
	height: 20px
}

.aiicHc[data-browser='2'] {
	background: no-repeat url(https://www.gstatic.com/identity/boq/accountsettingssecuritycommon/images/sprites/browsers-7aaa748b21991550f4cff35726a2e195.png) 0 -84px;
	background-size: 20px 167px;
	width: 20px;
	height: 20px
}

.aiicHc[data-browser='3'] {
	background: no-repeat url(https://www.gstatic.com/identity/boq/accountsettingssecuritycommon/images/sprites/browsers-7aaa748b21991550f4cff35726a2e195.png) 0 -105px;
	background-size: 20px 167px;
	width: 20px;
	height: 20px
}

.aiicHc[data-browser='4'] {
	background: no-repeat url(https://www.gstatic.com/identity/boq/accountsettingssecuritycommon/images/sprites/browsers-7aaa748b21991550f4cff35726a2e195.png) 0 -126px;
	background-size: 20px 167px;
	width: 20px;
	height: 20px
}

.aiicHc[data-browser='5'] {
	background: no-repeat url(https://www.gstatic.com/identity/boq/accountsettingssecuritycommon/images/sprites/browsers-7aaa748b21991550f4cff35726a2e195.png) 0 -63px;
	background-size: 20px 167px;
	width: 20px;
	height: 20px
}

.aiicHc[data-browser='6'] {
	background: no-repeat url(https://www.gstatic.com/identity/boq/accountsettingssecuritycommon/images/sprites/browsers-7aaa748b21991550f4cff35726a2e195.png) 0 0;
	background-size: 20px 167px;
	width: 20px;
	height: 20px
}

.aiicHc[data-browser='7'] {
	background: no-repeat url(https://www.gstatic.com/identity/boq/accountsettingssecuritycommon/images/sprites/browsers-7aaa748b21991550f4cff35726a2e195.png) 0 -21px;
	background-size: 20px 167px;
	width: 20px;
	height: 20px
}


/*threeedot menu*/
.dotmenubody {
	overflow-x: hidden;
	overflow-y: auto;
	-webkit-overflow-scrolling: touch
}

.dotinnermenubody {
	float: left;
	padding: 16px 0
}
.dotmenufull.dotmenufull2 {
	transition: left .2s cubic-bezier(0.0, 0.0, 0.2, 1), max-width .2s cubic-bezier(0.0, 0.0, 0.2, 1), max-height .2s cubic-bezier(0.0, 0.0, 0.2, 1), opacity .05s linear, top .2s cubic-bezier(0.0, 0.0, 0.2, 1)
}
.dotmenufull1 .dotinnermenubody {
	padding: 8px 0
}

.dotinnermenuitem {
	color: #222;
	cursor: pointer;
	display: block;
	outline: none;
	overflow: hidden;
	padding: 0 24px;
	position: relative
}

.dotinnermenuitemname {
	box-flex: 1;
	flex-grow: 1;
	min-width: 0
}

.uyYuVb {
	display: flex;
	font-size: 14px;
	font-weight: 400;
	line-height: 40px;
	height: 40px;
	position: relative;
	white-space: nowrap
}

.dotmenufull.e5Emjc .dotinnermenuitem {
	padding-left: 64px
}

.dotmenufull.CblTmf .dotinnermenuitem {
	padding-right: 48px
}
.dotinnermenuitem.RDPZE .PCdOIb {
	opacity: .26
}

.dotinnermenuitem.FwR7Pc {
	outline: 1px solid transparent;
	background-color: #eeeeee
}

.dotinnermenuitem.RDPZE {
	color: #b8b8b8;
	cursor: default
}

.dotinnermenuitem.N2RpBe::before {
	transform: rotate(45deg);
	transform-origin: left;
	content: "\0000a0";
	display: block;
	border-right: 2px solid #222;
	border-bottom: 2px solid #222;
	height: 16px;
	left: 24px;
	opacity: .54;
	position: absolute;
	top: 13%;
	width: 7px;
	z-index: 0
}

.dotmenufull.CblTmf .dotinnermenuitem.N2RpBe::before {
	left: auto;
	right: 16px
}

.dotinnermenuitem.RDPZE::before {
	border-color: #b8b8b8;
	opacity: 1
}

.dotmenufull.CblTmf .dotinnermenuitem.N2RpBe::before {
	left: auto;
	right: 16px
}
.dotinnermenuitem.qs41qe>.aBBjbd {
	animation: quantumWizBoxInkSpread .3s ease-out;
	animation-fill-mode: forwards;
	background-image: radial-gradient(circle farthest-side, #bdbdbd, #bdbdbd 80%, rgba(189, 189, 189, 0) 100%);
	background-size: cover;
	opacity: 1;
	top: 0;
	left: 0
}
.dotmenufull1 .dotinnermenuitem {
	color: #202124
}

.dotmenufull1 .dotinnermenuitem.FwR7Pc {
	background-color: rgba(232, 234, 237, 0.4)
}

.dotmenufull1 .dotinnermenuitem.qs41qe>.aBBjbd {
	background-image: radial-gradient(circle farthest-side, #dadce0, #dadce0 80%, rgba(218, 220, 224, 0) 100%)
}

@media screen and (max-width:840px) {
	.dotinnermenubody {
		padding: 8px 0
	}
	.dotinnermenuitem {
		padding: 0 16px
	}
	.dotmenufull.e5Emjc .dotinnermenuitem {
		padding-left: 48px
	}
	.PCdOIb {
		left: 12px
	}
}


.dotmenufull1.dotmenufull {
	background-color: #fff;
	box-shadow: 0 1px 2px 0 rgba(60, 64, 67, .30), 0 2px 6px 2px rgba(60, 64, 67, .15);
	border-radius: 4px
}

/*Display and hide the  menu*/
.toggle-content {
	display: none;
}

.toggle-content.is-visible {
	display: block;
}


	</style>

	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<script>
		jQuery(document).ready(function(){
			jQuery('.remove-device').on('click', function(){
				const id = $(this).data('id');
				const devicename = $(this).data('devicename');
				const accesstoken = $(this).data('accesstoken');
				$('#accesstoken').val(accesstoken); 
			 $('#devicename').html(devicename); 
				$('#deleteFormDevice').attr('action', '/user/sessiondestroy/'+ id );
				jQuery('#removeDevice').modal('show');
			});
			jQuery('.add-device-name').on('click', function(){
				const id = $(this).data('id');
				const accesstoken = $(this).data('accesstoken');
				$('#accesstoken').val(accesstoken);
				$('#DeviceNameUpdate').attr('action', '/user/personaldevicename/'+ id );
				jQuery('#changeDeviceName').modal('show');
			});

			jQuery('.cancel-subscription').on('click', function(){
				jQuery('#cancelSubscription').modal('show');
			});

			jQuery('.change-password').on('click', function(){
				jQuery('#changePassword').modal('show');
			});

@if ($errors->has('devicename'))
console.log("it has devicename error");
const id = {{  session()->get( 'deviceidseesion' )}};
console.log("gotid",id);
				$('#DeviceNameUpdate').attr('action', '/user/personaldevicename/'+ id );
$('#changeDeviceName').modal('show');

@endif
@if ($errors->has('old_password')||$errors->has('password')||$errors->has('password_confirmation') )
		jQuery('#changePassword').modal('show');
		@endif

// Check all 
$('#checkall').click(function(){
      if($(this).is(':checked')){
         $('.update_check').prop('checked', true);
      }else{
         $('.update_check').prop('checked', false);
      }
   });
		$(document).on('click', '.update_record', function(){
        var id = [];var status;
        
        if(confirm("Are you sure you want to Update this data?"))
        {
            $('.update_check:checked').each(function(){
                id.push($(this).val());
            });
            console.log("id",id); 
            if(id.length > 0)
            {   
              
              status= this.id;
                $.ajax({
                    url:"{{url('admin/multisessiondestroy') }}",
                    headers: {'x-csrf-token': '{{ csrf_token() }}'},
                    method:"post",
                    data:{id:id,status:status},
                    success:function(data)
                    { 
                      console.log(data);
                        alert(data.message);
                        // console.log(data.message);
                        // console.log('data',data);
                        $.each(id, function( index, value ) {
                         // alert( index + ": " + value );
                          $('#'+value+'').fadeTo(400, 0, function () { 
                           $(this).remove();
                            });
                       });
                    },
          error: function (xhr, status, error) {
            alert(xhr.responseJSON.message);
            console.log(xhr.responseJSON);
          //   $.each(xhr.responseJSON.errors, function (key, item) 
          // {
          //   $("#errors").append("<li class='alert alert-danger'>"+item+"</li>")
          // });
          }
                });
            }
            else
            {
                alert("Please select atleast one checkbox");
            }
        }
    });
		});
	    function myFunction(el) {
  el.classList.toggle("iWO5td");
//   el.parentElement
el.nextElementSibling.classList.toggle("is-visible");
//   console.log("el",el.parentElement);
//   console.log("next",el.nextElementSibling);
}
		

		</script>
@endsection

@section('content')
	

	<section class="page-section">

		<div class="modal fade" id="changePassword">
	    <div class="modal-dialog">
			<div class="modal-content">

				<div class="modal-body text-center">
					@if (count($errors) > 0)
          <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          @endif
					 <form action="{{ url("/userupdate", [Auth::user()->id]) }}" class="text-left" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

						<div class="form-group {{ $errors->has('old_password') ? ' has-error' : '' }}">
							<label for="oldpassword">Old Password</label>
							<input type="password" class="form-control" name="old_password" id="oldpassword">
						</div>

						<div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
							<label for="newpassword">New Password</label>
							<input type="text" class="form-control" name="password" id="newpassword">
						</div>

						<div class="form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
							<label for="cnewpassword">Confirm New Password</label>
							<input type="password" class="form-control" name="password_confirmation" id="cnewpassword">
						</div>

						<div class="text-center">
							<button type="submit" class="btn btn-sm" style="background: #C4C4C4 0% 0% no-repeat padding-box;padding: 0.5rem 1.5rem;">
								Update
							</button>
							{{-- <a class="btn btn-sm" href="javascript:void(0)" style="background: #C4C4C4 0% 0% no-repeat padding-box;padding: 0.5rem 1.5rem;">Change Password</a> --}}
						</div>
					</form>
				</div>

			</div>
	    </div>
	</div>
		<div class="modal fade" id="changeDeviceName">
	    <div class="modal-dialog">
			<div class="modal-content">

				<div class="modal-body text-center">
					@if (count($errors) > 0)
          <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          @endif
						<form  id="DeviceNameUpdate" method="POST" >
            @csrf
            @method('PUT')

						<div class="form-group {{ $errors->has('devicename') ? ' has-error' : '' }}">
							<label for="devicename">Device Name</label>
							<input type="text" class="form-control" name="devicename" id="devicename" maxlength="20">
						</div>


						<div class="text-center">
							<button type="submit" class="btn btn-sm" style="background: #C4C4C4 0% 0% no-repeat padding-box;padding: 0.5rem 1.5rem;">
								Update
							</button>
							{{-- <a class="btn btn-sm" href="javascript:void(0)" style="background: #C4C4C4 0% 0% no-repeat padding-box;padding: 0.5rem 1.5rem;">Change Password</a> --}}
						</div>
					</form>
				</div>

			</div>
	    </div>
	</div>

	<div class="modal fade" id="removeDevice">
		<div class="modal-dialog">
		<div class="modal-content">
 
			<div class="modal-body text-center">
				<h4>Remove Device</h4>
				<p><span id="devicename"></span> will be removed. Would you like to continue?</p>
				<div>
					<form  method="POST" id="deleteFormDevice">
						<input type="hidden" name="_method" value="DELETE">
														<input type="hidden" name="_token" value="{{ csrf_token() }}">
														<input type="hidden" name="access_token" id="accesstoken">
					{{-- <a class="btn btn-sm" href="javascript:void(0)" style="background: #C4C4C4 0% 0% no-repeat padding-box;padding: 0.5rem 1.5rem;">Yes</a> --}}
					<input style="background: #C4C4C4 0% 0% no-repeat padding-box;padding: 0.5rem 1.5rem;" type="submit" class="btn btn-sm" value="Delete">
					<a class="btn btn-sm" href="javascript:void(0)" data-dismiss="modal" style="border: 1px solid #707070;border-radius: 6px;padding: 0.5rem 1.5rem;">No</a></form>
				</div>
			</div>

		</div>
		</div>
</div>
@if(session()->has('message'))
 <div class="alert alert-success alert-dismissible ">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		{{ session()->get('message') }}
</div>
@endif
		<div class="container">
				<h2 class="section-heading text-center">Account Settings</h2>
				
				<div class="">
				<th>Check All <input type="checkbox" class='checkall' id='checkall'><input type="button" class='update_record' id="0" value='InActivate selected' ></th>
   <br> <p>Devices {{count($allSession)}}/{{GetLimitForUser(Auth::user()->id)}}</p>
					<div class="device-body1 device-body" role="list" jsname="rymPhb" jscontroller="staXac">
                  @foreach($allSession as $key => $device)
                  <div data-item-id="{{$key+1}}" id="{{$device->id}}" class="F4S0Db" role="listitem" style="z-index: 0;position: relative;">
                     <div class="device-box-card device-box-card-classb KC1dQ Usd1Ac AaN0Dd  jpoelc" jscontroller="zN4bWc" jsaction="JIbuQc:Hfqgkf;" data-device-id="A8b4a0279f3be6a6c" {!!$device->device['classes']??''!!}>
                        <div class="device-box-card-inside device-box-card-inside-classb" tabindex="0" jscontroller="tKHFxf" jsaction="click:cOuCgd; mousedown:UX7yZ; mouseup:lbsD7e; mouseenter:tfO1Yc; mouseleave:JywGue; touchstart:p6p2H; touchmove:FwuNnf; touchend:yfqBxc; touchcancel:JMtRjd; focus:AHmuwe; blur:O22p3e; contextmenu:mg9Pef;">
                           <div class="device-details">
                              <div class="device-logo device-logo-div" {!!$device->device['classes']??''!!}></div>
                           </div>
                           <div class="midline"></div>
                           <div class="device-detail-txt">
                              <div class="device-name-div">
                                 <div role="heading" aria-level="3" class="device-name">{{$device->device['platform']??$device->device['device']??''}}</div>
																 @isset($device->device['personaldevicename'])
																 <div class="personal-device-name">{{$device->device['personaldevicename']}}</div>
																 @endisset
                                 
                              </div>
                              <div class="device-location">
                                 <div>{{$device->user_ip}}</div>
                                 <div jsname="WQnlqf"> {{ \Carbon\Carbon::parse($device->created_on)->diffForHumans() }}</div>
                              
                                 
                                 @if(session()->get('jwttoken')==$device->access_token)
                                 <div class="K45rif">
                                    <svg class="kwfIKc mMF6qc" width="14" height="14" viewBox="0 0 24 24" xmlns="http://www.w4.org/2000/svg">
                                       <path d="M0 0h24v24H0z" fill="none"></path>
                                       <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"></path>
                                    </svg>
                                    This device
                                 </div>
                                 @endif
                                 @isset($device->device['browser'])
                                 <div class="eIXuRc EGriic">
                                    <div class="NQ9Zpd" role="heading" aria-level="4"><span>Browsers you’re signed in on</span></div>
                                    <div>
                                       <div class="YrUtyd" role="list">
                                          <div class="Qc8ulc" role="listitem">
                                             <span class="CCq0jd">
                                                <div class="aiicHc" {!!$device->device['browserclass']??''!!}></div>
                                             </span>
                                             <span title={{$device->device['browserversion']??''}}>{{$device->device['browser']}}</span>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 @endisset
                              </div>
                           </div>
                        </div>

						<div class="TDjQOc" data-device-id="Acb514901592fd168" data-source="3">
	<div role="button" class="dotmenu dotmenu1 dotmenu2 " onclick="myFunction(this)" aria-label="More info" aria-disabled="false" tabindex="0" aria-haspopup="true" aria-expanded="false" data-menu-corner="top-end" data-anchor-corner="bottom-end">
		<div class="NWlf3e MbhUzd" style="top: 21px; left: 23.5625px; width: 48px; height: 48px;"></div> <span jsslot="" class="MhXXcc oJeWuf">
																<span class="Lw7GHd snByac">
																	 <svg width="24" height="24" viewBox="0 0 24 24" focusable="false" class="gnN5i NMm5M">
																			<path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"></path>
																	 </svg>
																</span> </span>
	</div>
	<div class="dotmenufull dotmenufull1 dotmenufull2 toggle-content" role="menu" tabindex="0" data-back-to-cancel="false" style="position: absolute; max-width: 256px; max-height: 136px;z-index:1;
	right: 1%; top: 100%;
	">
		<div class="dotmenubody" style="max-width: 256px; max-height: 136px;">
			<div class="dotinnermenubody dotinnermenubody1" style="width: 256px; height: auto; min-width: 256px;"> <span class="dotinnermenuitem" aria-label="Sign out this device" role="menuitem" tabindex="-1">
							<div class="aBBjbd MbhUzd" jsname="ksKsZd"></div>
							<div class="uyYuVb oJeWuf" jsname="cEoysc">
								 <div class="dotinnermenuitemname remove-device" data-id="{{ $device->id }}" data-devicename="{{$device->id}}.{{ $device->device_name }}" data-accesstoken="{{ $device->access_token }}" >Sign out</div>
							</div>
					 </span> <span jsslot="" class="dotinnermenuitem" aria-label="Find phone" role="menuitem" tabindex="-1">
							<div class="aBBjbd MbhUzd" jsname="ksKsZd"></div>
							<div class="uyYuVb oJeWuf" jsname="CUoVIc">
								 <div class="dotinnermenuitemname add-device-name" data-id="{{ $device->id }}" data-devicename="{{$device->id}}.{{ $device->device_name }}" data-accesstoken="{{ $device->access_token }}">Add a name</div>
							</div>
					 </span> <span style="display: none;" jsslot="" class="dotinnermenuitem" aria-label="I do not recognize this device" role="menuitem" tabindex="-1">
							<div class="aBBjbd MbhUzd" jsname="ksKsZd"></div>
							<div class="uyYuVb oJeWuf" jsname="nJTW3e">
								 <div class="dotinnermenuitemname">Don’t recognize this device?</div>
							</div>
					 </span> </div>
		</div>
	</div>
</div>
						<div class="TDjQOc2" data-device-id="Acb514901592fd168" data-source="3">
	<div role="button" class="dotmenu dotmenu1 dotmenu2 "  aria-label="More info" aria-disabled="false" tabindex="0" aria-haspopup="true" aria-expanded="false" data-menu-corner="top-end" data-anchor-corner="bottom-end">
		<div class="NWlf3e MbhUzd" style="top: 21px; left: 23.5625px; width: 48px; height: 48px;"></div> <span jsslot="" class="MhXXcc oJeWuf">
																<span class="Lw7GHd snByac">
																<input type='checkbox' class='update_check' id='delcheck_{{$device->id}}'  value='{{$device->id}}'>
																</span> </span>
	</div>
</div>
                        <span class="VfPpkd-BFbNVe-bF1uUb NZp2ef" aria-hidden="true"></span>
                     </div>
                  </div>
                  @endforeach
                        
                     </div>
                         </div>
				


				<div class="row setting-cards mt-5">
					<div class="col-centered col-md-8">
						<ul class="setting-card">
							<li>Profile Management</li>
							<li>
								Email:
							<span class="pull-right">{{Auth::user()->email}}<i class="fa fa-edit light-brown" aria-hidden="true"></i></span>
							</li>
							<li>
								Password:
								<span class="pull-right">
									<a href="javascript:void(0)" class="change-password">Change password</a>
								</span>
							</li>
							@if ( Auth::user()->subscribed('main'))
								<li><details>
    <summary>Get PassCode for logging in TV</summary>
    {{$hasid}}
</details></li>
@endif
						</ul>
					</div>
				</div>
					
		</div>
		
	</section>
	
@endsection