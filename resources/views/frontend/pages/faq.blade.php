@extends('frontend/partials/master')

@section('stylesheets-bottom')
	<style>
		nav#mainNav {
			background-color: #004E92;
		}
	</style>

	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

	<style>

		body{
			background: #F9F9F9 0% 0% no-repeat padding-box;
		}


		.full-width {
			background: #000000 0% 0% no-repeat padding-box;
		}

		.col-centered {
		    margin: 0 auto;
		    position: relative;
		}

		.channel-detail {
			position: absolute;
		    top: 20%;
		    right: -10%;
		    color: #ffffff;
		    padding: 20px;
		    background: #333333 0% 0% no-repeat padding-box;
		    box-shadow: 0px 3px 6px #00000029;
		    opacity: 1;
		    backdrop-filter: blur(30px);
		    -webkit-backdrop-filter: blur(30px);
		}

		.channel-detail:before {
		    content: '';
		    width: 0px;
		    height: 0px;
		    background-color: transparent;
		    z-index: 999;
		    display: block;
		    left: -20px;
		    position: absolute;
		    border-left: 10px solid transparent;
		    border-top: 10px solid transparent;
		    border-right: 10px solid #333333;
		    border-bottom: 10px solid transparent;
		}

		.live-news-item {
		    padding: 2rem 0;
		    text-align: center;
		    background: #FFFFFF 0% 0% no-repeat padding-box;
		    box-shadow: 0px 3px 6px #00000029;
		    border-radius: 8px;
		}

		.custom-row .row{
			margin-top: 2rem;
		}

		.live-news-item:after {
		    content: 'live';
		    position: absolute;
		    right: 12px;
		    top: 12px;
		    color: white;
		    background: #E10C26 0% 0% no-repeat padding-box;
		    border-radius: 4px 0px 0px 4px;
		    padding: 0px 5px;
		    line-height: 1;
		}

		.faq-btn{
			background: #004E92 0% 0% no-repeat padding-box;
			border: 0.25px solid #707070;
			border-radius: 18px;
			color: white;
			padding: 0.4rem 1rem;
		}

		.faq-btn.collapsed {
			background: #FFFFFF 0% 0% no-repeat padding-box;
			color: #5E5E5E;
		}

		#accordion-child .card {
		    margin-top: 5px;
		}

		[data-toggle="collapse"] .fa:before {   
		  content: "\f107";
		}

		[data-toggle="collapse"].collapsed .fa:before {
		  content: "\f106";
		}

		.card-header {
		    background: #FFFFFF 0% 0% no-repeat padding-box;
		    box-shadow: 0px 1px 2px #00000029;
		    border-radius: 2px !important;
		}

		#tou {
			background: #FFFFFF 0% 0% no-repeat padding-box;
			box-shadow: 0px 1px 2px #00000029;
			border-radius: 2px;
			padding: 1rem;
		    text-align: left;
		    letter-spacing: 0.26px;
		    color: #989898;
		}

		#pp {
			background: #FFFFFF 0% 0% no-repeat padding-box;
			box-shadow: 0px 1px 2px #00000029;
			border-radius: 2px;
			padding: 1rem;
		    text-align: left;
		    letter-spacing: 0.26px;
		    color: #989898;
		}

		#pp h5{
			color: #5E5E5E;
		}

		a.card-link {
		    color: #5E5E5E;
		}

	</style>
@endsection

@section('content')

	<section class="page-section custom-row">

		<div class="container" style="margin-top: 4%;">
			<h4 class="section-heading text-center">FAQ</h4>
			<div class="row">
				<div class="col-centered">
					<a href="#faq-question" class="btn btn-sm btn-default faq-btn" data-toggle="collapse">FAQ</a>
				
					<a href="#tou" class="btn btn-sm btn-default collapsed faq-btn" data-toggle="collapse">Terms of uses</a>
				
					<a href="#pp" class="btn btn-sm btn-default collapsed faq-btn" data-toggle="collapse">Privacy Policy</a>
				</div>
			</div>

			<div class="row" id="accordion">
				<div id="faq-question" class="col-sm-12 collapse show" data-parent="#accordion">
					

					<div id="accordion-child">
					    <div class="card">
					      <div class="card-header">
					        <a class="collapsed card-link" data-toggle="collapse" href="#collapseOne">
					          	What is Streamly live TV?
					        	<i class="fa pull-right" aria-hidden="true"></i>
					        </a>

					        

					      </div>
					      <div id="collapseOne" class="collapse" data-parent="#accordion-child">
					        <div class="card-body">
					          Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
					        </div>
					      </div>
					    </div>
					    <div class="card">
					      <div class="card-header">
					        <a class="collapsed card-link" data-toggle="collapse" href="#collapseTwo">
					        	What can I do Streamly live TV?
					      		<i class="fa pull-right" aria-hidden="true"></i>
					      	</a>

					      

					      </div>
					      <div id="collapseTwo" class="collapse" data-parent="#accordion-child">
					        <div class="card-body">
					          Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
					        </div>
					      </div>
					    </div>
					    <div class="card">
					      <div class="card-header">
					        <a class="collapsed card-link" data-toggle="collapse" href="#collapseThree">
					          	Who can use Streamly live TV?
					        	<i class="fa pull-right" aria-hidden="true"></i>
					        </a>

					        

					      </div>
					      <div id="collapseThree" class="collapse" data-parent="#accordion-child">
					        <div class="card-body">
					          Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
					        </div>
					      </div>
					    </div>
					</div>


				</div>

				<div id="tou" class="col-sm-12 collapse" data-parent="#accordion">
					<p><h2>Streamly Terms of Use
						Effective June 1, 2020</h2>
						
						 
						
						<p>Welcome to STREAMLY! Our goal is to provide you with outstanding television content at a great price.
						
						Please note that the terms and conditions contained herein, together with any applicable promotional terms agreed to by you (“Terms and Conditions” and together with the Privacy Policy and End User License Agreement (“EULA”) each of which is hereby incorporated in these Terms of Use by this reference in its entirety, collectively, these “Terms of Use”) include embedded hyperlinks that may only be accessed through our websites, and if you are reviewing these Terms of Use through certain applications or other platforms, then you may need to visit www.streamlytv.com in your browser to review any hyperlinked terms incorporated herein. These Terms of Use set forth the terms and conditions pursuant to which STREAMLY will agree to provide or otherwise make our Services available to you. Your access and use is conditioned on your acceptance of these Terms of Use and are effective until we change or replace them.
						
						STREAMLY Inc. and its affiliates (referred to as, “we,” “us,” “our” or “STREAMLY”) endeavors to provide you and other users of our websites (“Websites”) and our applications (“Apps”) with access to a variety of content (such Websites and Apps together with any other “STREAMLY” branded memberships, features, functionalities, products, services, and any and all content contained therein collectively referred to in these Terms of Use as, “Services”). You and other users of our Services are collectively referred to in these Terms of Use as “you,” “your,” and “Users.” Our Services, including transaction and subscription based Services, were designed to be accessed and used only by those Users intentionally authorized by us to receive such Services after registering with us and creating a membership account (“Membership Account”) to receive an assortment of audio, video, data, interactive, and other content available (each, an “Authorized User”). Notwithstanding anything to the contrary set forth in these Terms of Use. 
						
						SUBJECT TO CERTAIN EXCEPTIONS SET FORTH BELOW IN SECTION 11, THESE STREAMLY TERMS OF USE REQUIRE THE USE OF MANDATORY AND BINDING ARBITRATION ON AN INDIVIDUAL BASIS TO RESOLVE DISPUTES (AS THAT TERM IS DEFINED IN SECTION 11.A. BELOW, RATHER THAN, AMONG OTHER THINGS, JURY TRIALS OR CLASS ACTIONS (SEE SECTION 11 BELOW).
						</p>
						 
						
						<p>Terms of Use: Table of Contents
						Section 1: ACCEPTANCE OF TERMS OF USE<br>
						Section 2: OUR SERVICES<br>
						Section 3: MEMBERSHIP ACCOUNTS<br>
						Section 4: TRANSACTIONAL AND SUBSCRIPTION SERVICES; PACKAGES AND LEVELS; PROMOTIONS AND OTHER PACKAGES<br>
						Section 5: BILLING<br>
						Section 6: CUSTOMER SUPPORT SERVICES AND RELATED COMMUNICATIONS<br>
						Section 7: INTELLECTUAL PROPERTY<br>
						Section 8: DISCLAIMER OF WARRANTIES<br>
						Section 9: LIMITATION OF LIABILITY<br>
						Section 10: INDEMNIFICATION<br>
						Section 11: DISPUTE RESOLUTION, ARBITRATION AGREEMENT AND CLASS ACTION WAIVER<br>
						Section 12: MISCELLANEOUS<br>
						Privacy Policy: Click here.
						End-User License Agreement: Click here.</p>
						 
						Section 1: Acceptance of Terms of Use
						1. Acceptance.   Acceptance. Our Services are not intended to be used by children without involvement and approval of a parent or other legal guardian. If you are under the age of 13, then you are not permitted to register with us or provide your personal information to us. If you are between the ages of 13 and 18, you may register with us only if your parent or legal guardian has read and agreed to these Terms of Use and you have the consent of your parent or legal guardian. These Terms of Use govern your use of all of our Services and your relationship with us. By accessing, visiting, downloading or using any of our Services (e.g., if you download any of our Apps onto any device) you confirm that you have read and agreed to these Terms of Use. If you do not agree to these Terms of Use, then do not access or use any of our Services. If you are an existing user and do not wish to accept any revised Terms of Use, then you must immediately stop using all of our Services (including, for clarity, permanently removing all Apps from your devices), except as set forth in Section 11 below. Certain of our Services may be made available without any requirement to pay a fee, and you hereby acknowledge and agree that any such access to or use are also governed by these Terms of Use.
						<br>
						2. Changes to these Terms of Use.   These Terms of Use are effective until we change them. Because we are continuously working to improve, enhance, expand, and modify our Services to provide you with the best experience, these Terms of Use will change from time to time, and we suggest that you check back regularly in order to understand our Services, including our practices related to personal information as described in the Privacy Policy, the rights and restrictions related to certain of our Services granted in the EULA, and all other terms and conditions governing your access and use of our Services. You acknowledge and agree that we may change all or any portion of these Terms of Use at any time and from time to time, in our sole and absolute discretion, by publishing a revised version of these Terms of Use at: www.streamlytv.com. Any revised version of these Terms of Use will be effective immediately for new Users, and solely with respect to existing Users, such revised version will be effective 30 days following the day on which the revisions were first posted, unless expressly and specifically stated otherwise. Except as otherwise set forth in Section 2.E., in the event that you are an existing STREAMLY customer and you do not wish to accept any revised version of the Terms of Use, then you must immediately send a notice to us at: Attn: General Counsel's Office 9601 S. Meridian Blvd., Englewood, CO 80112, and we will, at our option, either cancel your Membership Account at the end of the then-current billing period or allow you to continue to receive your Subscription Services under the previous version of these Terms of Use.
						<br>
						3. Certain Restrictions on Use; Private and Personal Use Only.   Some programming is the copyrighted material of the third party that supplies it, is protected by copyright and other applicable laws and may not be reproduced, published, rebroadcast, rewritten or redistributed without the written permission of the third party that supplied it (except as permitted by the “fair use” or other applicable provisions of the U.S. copyright laws) or by our agreements with such third parties. Our Services, including all content viewed through our Services, are solely for private, personal, non-commercial use, not for use in the operation of a business or service bureau, and not for any other public viewing, for profit or for the benefit or any person or entity other than the Authorized User; and your limited rights to use our Services as set forth in our EULA are subject to your understanding of, and agreement to, these Terms of Use in their entirety.
						<br>
						Section 2: OUR SERVICES<br>
						1. Our Services.   By subscribing to our Services, you receive access to and use of: (i) certain software, technology and other intellectual property rights (including all functionality and data contained therein) (e.g., adaptive streaming and third-party platform authentication) owned or operated by us (regardless whether available for download directly or indirectly from STREAMLY) (e.g., applications that are hosted or downloaded, accessories, tools, features and/or functionality related to and/or made available by us); (ii) websites owned, operated or otherwise controlled by, or on behalf of, us; (iii) the content accessible by you (e.g., any and all transaction and subscription video, audio, data, interactive, programming, advertising and other content); and (iv) any and all other products and/or services accessed, provided, used or otherwise made available to you, including to enhance past or then-current Services.
						<br>
						2. Internet Connection.   In order to access and use our Services, you must have a broadband, wireless or similar internet connection, and you will need to obtain such internet connection (including sufficient levels and required equipment related thereto) from a third-party internet or similar wireless service provider at your sole cost and expense (“Internet Connectivity”). It is your responsibility to obtain and maintain the Internet Connectivity necessary to access and use our Services. STREAMLY is not responsible for your Internet Connectivity (or the amount of data consumed by you in connection with your use of any of our Services). The time it takes to access and use any of our Services (e.g., watching a movie or TV show) will vary based on a number of factors, including your location, your Internet Connectivity, the available bandwidth at the time of your request, the format of our Services you are attempting to access, the device(s) you use to access our Services and other factors outside of our control. STREAMLY makes no representations or warranties about the speed or quality of your watching experience on your or any device, and reserves the right change our Services (e.g., the format) based on the specifications of your Internet Connectivity (e.g., if your Internet Connection or device do not meet our required standards for accessing and viewing high-definition (“HD”) content, then any HD content accessed by you may be automatically re-formatted by us in an attempt to improve your experience). We suggest that you ensure that your Internet Connectivity, Permitted Device(s) (as defined below) and configuration are compatible with our Services and the format thereof. You can review our minimum requirements and recommended levels for Internet Connectivity at our website. By accessing or using our Services, you agree to look solely to the entity that provides your Internet Connection for any issues related to such connection and/or its compatibility with, or sufficiency for accessing and using, any of our Services.
						<br>
						3. Devices.   Not all devices are compatible, or permissible, for use with our Services. For a current list of devices permitted for use with our Services (“Permitted Devices”) please visit and review the devices listed at: www.streamlytv.com. Permitted Devices, including Permitted Cloud DVR Devices (as defined in Section 2.G.iii. below), are manufactured and sold by entities other than STREAMLY. In order to use our Services, you must have a Permitted Device, and you will need to obtain such Permitted Device from a third-party provider of such device at your sole cost and expense. You may use more than one Permitted Device, but the number of Authorized User(s) concurrently accessing or using our Services may be limited to one (1) Permitted Device at any time. In addition, certain of our Services, or portions of the functionality thereof, may be “blacked-out” or not-available on certain Permitted Device(s). By accessing or using any of our Services, you agree to look solely to the third party that manufactured and/or sold you the device for any issues related to such device, the operating system thereon and/or its or their compatibility with any of our Services.
						<br>
						4. Use of Adaptive Bitrate Streaming.   Our Services, including, without limitation, live TV channels (as defined below), use adaptive bitrate streaming (“ABS”), which creates multiple versions of each program, recorded at different bitrates, to optimize playback of the applicable audiovisual content at any given time based upon the capabilities of the applicable playback device and available internet bandwidth. If you choose to use the Cloud DVR (Digital Video Recording), if made available in the future, you are directing that playback be provided using ABS.
						<br>
						5. Service Changes; Packaging of Services.   In addition to, and without limiting any other provisions in these Terms of Use, STREAMLY may, permanently or temporarily, add, delete, rearrange, alter, interrupt, change, and/or eliminate: (i) any and all prices, fees and/or charges; (ii) packages, programming, programming suppliers, services offered by suppliers; (iii) availability of Cloud DVR with respect to any particular programming and/or programming suppliers, (iv) software, applications, features and/or functionalities and technical and/or functional requirements, (v) Permitted Devices, (iv) compatible operating systems, (iiv) and/or minimum Internet Connectivity required to use the Services, from time to time and at any time. In the event that we add, alter and/or change any prices, fees and/or charges, then you agree to pay such added, altered, and/or changed prices, fees and/or charges. In the event that we add, delete, rearrange, alter, change and/or eliminate any packages, availability of Cloud DVR with respect to any particular programming and/or programming suppliers, software, applications, features and/or functionalities, then you acknowledge and agree that (a) we have no obligation to replace or supplement such packages, availability of Cloud DVR with respect to any particular programming and/or programming suppliers, software, applications, features and/or functionalities, and (b) you are not entitled to any credits, refunds, price reductions or any other form of compensation because of any such addition, deletion, rearrangement, alteration, change and/or elimination. You further acknowledge and agree that such additions, deletions, rearrangements, alterations, changes and/or eliminations are not a discretionary act by us if they are due, in whole or in part, to the termination, suspension or expiration of our legal right to provide such packages, availability of the Cloud DVR with respect to any particular programming and/or programming suppliers, software, applications, features and/or functionalities.
						<br>
						6. Programming Content and Restrictions.   Certain of our Services may be “blacked out” in your viewing area (e.g., local broadcast channels, sporting events and/or other content for which you do not have the rights to access); if you circumvent or attempt to circumvent any of these “blackouts,” then you may be subject to legal action brought by us or third parties. Certain of our Services may have additional restrictions and your access to such Services will be subject to those restrictions (e.g., you must be at least 18 years of age, or the applicable age of majority where you live, in order to access certain adult-oriented programming or similar Services).
						<br>
						7. The Cloud DVR.<br>
						<br>i. Certain Cloud DVR subscribers will be participating in the Beta testing phase (when launched), which will only available to a limited number of subscribers until taken out of Beta. If you qualify and are selected to use the beta phase of the Cloud DVR, then we estimate that you will be able to record the number of hours shared on the www.streamly.com website, of video programming using the Cloud DVR during the beta testing period. Other Cloud DVR users are participating in the Cloud DVR general market product phase. If you have subscribed for use of the Cloud DVR general market product, then we estimate that you will be able to record approximately the designated number of hours of video programming using the Cloud DVR general market product. However, in any case, we do not guarantee any minimum amount of recording time and actual recording time may vary. The phrase “Cloud DVR” refers to both the Cloud DVR in the beta testing phase and the Cloud DVR general market product. We do not warrant or guarantee: (a) access to or recording of any particular programming; (b) the length of time any particular recorded program may remain available for your viewing; or (c) that the Cloud DVR (I) is error-free, (II) will not be interrupted, (III) will not erroneously delete programming that you record, or (IV) will not fail to schedule, make, store, or play back recordings. If you program the Cloud DVR to record more programming than it can hold in the space available, then some programs that you have previously recorded may be deleted. The Cloud DVR does not permit you to “protect” recordings from such deletion. We reserve the right to terminate the Cloud DVR (and/or all or any portion of your access to the Cloud DVR) at any time in our sole and absolute discretion and, for the avoidance of doubt, we may terminate the Cloud DVR (and/or all or any portion of your access to the Cloud DVR) even if you have not decided to cancel the Cloud DVR. In the event that the Cloud DVR (and/or your access to the Cloud DVR) is cancelled or terminated for any reason or no reason whatsoever, you will no longer have access to any recordings that you have made with the Cloud DVR. If your Membership Account is disconnected, suspended or terminated for non-payment, or for any other reason (or no reason) whatsoever (see, e.g., Section 5.E. below), then, even if you pay to restart your subscription(s) to our Services, recordings that you made with the Cloud DVR prior to such disconnection, suspension or termination may no longer be available.
						<br>ii. IN CONSIDERATION OF YOUR USE OF THE CLOUD DVR IN BETA TESTING PHASE OR THE CLOUD DVR GENERAL MARKET PRODUCT, AS APPLICABLE, YOU HEREBY RELEASE STREAMLY FROM ANY AND ALL LIABILITY AND RESPONSIBILITY FOR ANY: (a) INABILITY TO ACCESS OR RECORD ANY PROGRAMMING; (b) ERRONEOUS OR INTENTIONAL DELETION OF ANY RECORDING AT ANY TIME; (c) FAILURES, ERRORS AND INTERRUPTIONS OF THE CLOUD DVR; AND/OR (d) FAILURE OR INABILITY TO SCHEDULE, MAKE, STORE, OR PLAY BACK ANY RECORDING.
						<br>iii. Not all devices are compatible or permissible for use with the Cloud DVR. Currently, the only devices that are compatible and permissible for use with the Cloud DVR are: devices that are identified on the www.streamly.com website.
						<br>
						8. Compliance with Law.   You agree to use our Services, including all features and functionalities associated therewith, in accordance with all applicable laws (e.g., public performance limitations or other restrictions on any use of our Services or contents therein). You agree not to archive, download (other than through caching necessary for personal use), reproduce, distribute, modify, display, perform, publish, license, create derivative works from, offer for sale, or otherwise use or gain access to all or any portion of our Services, except as explicitly authorized in these Terms of Use, without express written permission from STREAMLY. You also agree not to: circumvent, remove, alter, deactivate, degrade, thwart or otherwise interfere with any of our Services; use any robot, spider, scraper or other automated means to access our Services; decompile, reverse engineer or disassemble any of our Services (e.g., software or other products); insert any code or product, or otherwise manipulate or interfere with our Services (e.g., running any of our Services on a device not permitted for use by us); or, use any data mining, data gathering, or extraction method. In addition, you agree not to upload, post, e-mail, or otherwise send or transmit any material designed to interrupt, interfere with, destroy, or limit the functionality of any of our Services, or other software, hardware, or telecommunications equipment associated with our Services (e.g., any software viruses or any other computer code, files, or programs). In the event that we learn that you are using any Service in a manner that constitutes infringement of third-party intellectual property rights, including rights granted by U.S. copyright law, your Membership Account will be terminated.
						<br>
						
						9. Multiple Services.  STREAMLY may offer other services to its product offering. Any account will have the ability to select only one service. Authorized devices will have access to that product offering only.
						<br>
						Section 3: MEMBERSHIP ACCOUNTS
						<br>1. Membership Account; Login Credentials.   Prior to accessing certain of our Services, you will be required to provide certain personal information determined by us as necessary to create a Membership Account. To become an Authorized User you must first register with us for a Membership Account. You will be required to create login credentials, comprised of a user name and password (“Login Credentials”) designed to enable access and use your Membership Account. You are responsible for any and all actions, omissions, or other activities related to your Membership Account, including any Authorized Users you permit to be linked to your Membership Account (if any, and only to the extent, permitted by us). The person in whose name any Membership Account is used for access to our Services, and whose payment method is charged, if any, is referred to as the “Account Owner.” As an Account Owner you may have access to and control over your Membership Account, any Authorized Users linked to your Membership Account, and the Services available in connection therewith. If you have a Membership Account, then your control of such Membership Account is exercised through your use and protection of your password. Please be aware that to maintain exclusive control and ensure compliance with these Terms of Use, you may not reveal or share your Login Credentials with anyone. In addition, if you would like to ensure that others cannot access your Membership Account by contacting our Customer Service (as defined in Section 6 below) and potentially altering your control, then you should take the necessary precautions not to reveal any personal or sensitive information used to identify yourself in connection with a lost password or user name (e.g., billing information, government issued IDs, or secret question answers). If you believe that your Membership Account has been accessed by anyone other than you or any Authorized Users you have permitted thereunder, or otherwise without permission or in any unauthorized manner, then you must contact us immediately at: 1 (888)-309-0838. You are responsible for updating and maintaining the truth and accuracy of the information provided to us relating to your Membership Account (e.g., current billing information for Subscription Services (as defined below)). YOU UNDERSTAND THAT BY SHARING ACCESS TO OUR SERVICES OR YOUR LOGIN CREDENTIALS, OR BY OTHERWISE ALLOWING OTHERS TO ACCESS YOUR ACCOUNT, YOU AGREE TO BE RESPONSIBLE FOR ASSURING THAT ANY AND ALL SUCH USERS COMPLY WITH THESE TERMS OF USE AND THAT YOU OR THE NAMED PERSON ON THE APPLICABLE MEMBERSHIP ACCOUNT SHALL BE RESPONSIBLE FOR ANY AND ALL ACTIONS, OMISSIONS AND/OR OTHER USE ASSOCIATED WITH SUCH MEMBERSHIP ACCOUNT. PLEASE BE AWARE THAT IF YOU SHARE OR OTHERWISE MAKE YOUR ACCOUNT AVAILABLE TO ANY THIRD PARTY (E.G., FORGETTING TO LOG OUT OF YOUR MEMBERSHIP ACCOUNT ON A SHARED OR PUBLIC DEVICE), THEN SUCH THIRD PARTY(IES) MAY BE ABLE TO ACCESS ANY AND ALL INFORMATION CONTAINED IN YOUR MEMBERSHIP ACCOUNT (E.G., CERTAIN PERSONAL INFORMATION, BILLING INFORMATION, SETTINGS, VIDEO RENTAL AND VIEWING HISTORY AND OTHER RELATED VIEWING PREFERENCES (IF ANY) ASSOCIATED WITH YOUR ACCOUNT).
						
						
						<br>2. Account Access; Cookies.   In order to provide you with ease of access to your Membership Account and to help administer our Services, STREAMLY uses technology that enables us to recognize you as an Authorized User and to provide you with direct access to your account without requiring you to retype any Login Credentials every time that you access, use, or otherwise revisit our Services, including via any Permitted Devices. For information about how cookies allow us to simplify the ease of access to your Membership Account, please review “Cookies, Web Beacons, Tags, Other Technologies and Online Tracking” section of our Privacy Policy.
						
						
						<br>3. Logging in Through Third-Party Accounts; Third-Party Accounts.   We may, but are not obligated, to permit you to elect to share certain information about your use of our Services (e.g., by posting what you watch or recommend with third-party accounts, on third-party platforms and such third parties you elect to connect to your Membership Account). Your use of any social features made available to you in connection with the Services is subject to the “Social Terms” section of our Privacy Policy. We suggest that you review our Privacy Policy, including the Social Terms, in its entirety before electing to connect your Membership Account, or otherwise share any information about you with any third party (e.g., by logging in via any social networking or other third-party account). We further suggest that you review the Privacy Policy of any such third-party account or third-party platform in its entirety before making the same election to connect your Membership Account thereto.
						
						
						<br>4. Requests for Information.   You should be mindful of any communication requesting that you submit any personal or other sensitive information (e.g., your personal information, credit or debit card account information, Login Credentials, or secrets questions), and before confirming any such request, you should contact Customer Service immediately at: 1 (888)-309-0838. Please be aware that providing your information in response to these types of communications can result in, among other things, identity theft or fraud. Always access your sensitive account information (e.g., your personal information, credit or debit card account information, Login Credentials, or secrets questions) via our Websites or Apps, or by contacting Customer Service directly at: 1 (888)-309-0838. In order to protect STREAMLY and its affiliates, media providers, device providers, business partners, or other service providers or licensors (collectively, “Contributors”), we reserve the right, and may, from time to time and at any time, without providing notice to you, place on hold (or otherwise suspend) your Membership Account in connection with any activity that we believe to be fraudulent, illegal, in violation of these Terms of Use, or otherwise suspicious. We may evaluate or elect to provide credits, refunds, price adjustments or other discounts, compensation or recompense, from time to time and at any time, in our sole and absolute discretion, BUT WE ARE NOT OBLIGATED, AND YOU ARE NOT ENTITLED AND HEREBY WAIVE ANY RIGHT, TO ANY CREDIT, REFUND, PRICE ADJUSTMENT OR ANY OTHER DISCOUNT, COMPENSATION OR RECOMPENSE FOR HOLDS PLACED ON YOUR ACCOUNT, IF ANY.
						
						
						<br>5. Device Deactivation.  If your device is transferred, lost, or stolen, then you must immediately call Customer Service at: 1 (888)-309-0838. It is your sole responsibility to notify us of any transferred, lost, or stolen devices, and to prevent any unauthorized access to our Services and your Membership Account. Please review the risks and responsibilities of allowing third-party access to your Membership Account (see Section 3.A. above) that may result from your failure to notify us that your device was transferred, lost, or stolen.
						
						 
						
						<br>Section 4: TRANSACTIONAL AND SUBSCRIPTION SERVICES; PACKAGES AND LEVELS; PROMOTIONS AND OTHER PACKAGES
						<br>1. Transactional Services.   Certain of our Services may be made available by us without requiring any commitment from you to pay any fees or other amounts (e.g., use of our Websites and Apps, access to any available content therein, and video-on-demand libraries) (collectively, “Transactional Services”). Your access to, and use of, any Transactional Services are governed by these Terms of Use, and once you create or request a Membership Account to access certain of our Services, you will be a member until you or we permanently cancel your Membership Account and all Subscription Services with us. For example, you must abide by these Terms of Use in connection with your creation and use of any Membership Account used solely to access our Services, regardless of whether you ever provide payment information for any other paid Transactional Services. You may be required to provide a payment method and necessary billing information in order to receive certain other Transactional Services (e.g., certain paid “a-la-carte” video-on-demand rentals or pay-per-view offerings). Any and all users that create a Membership Account automatically enroll in membership to our Services UNLESS AND UNTIL YOU CONTACT US TO PERMANENTLY CANCEL AND DELETE INFORMATION RELATED TO YOUR MEMBERSHIP ACCOUNT BY CALLING CUSTOMER SERVICE AT 1 (888)-309-0838, or until we elect to permanently cancel your Membership Account and Subscription Services (as defined below) for any purpose (e.g., in accordance with our standard information retention and destruction processes).
						
						
						<br>2. Subscription Services.   Certain of our Services may be made available by us solely in connection with your agreement to purchase a subscription to such Services for a set period (e.g., monthly or yearly subscription term) (collectively, “Subscription Services”). Regardless of the period for which you purchased any Subscription Services, following the completion of any such subscription period, your Subscription Services will automatically renew on a month-to-month basis at the then-current standard rates for monthly access to the same Subscription Services, unless and until they are cancelled or changed by you or STREAMLY in accordance with these Terms of Use.
						
						
						<br>3. Packages of Subscription Services.   We may offer a number of different packages of Subscription Services, including special promotional packages, each governed by any supplemental Promotional Terms provided at the time of your first access or use of such Services. Some promotional packages may be offered by third parties in conjunction with the provision of their own products and services. We are not responsible for the products and services provided by such third parties. We reserve the right to modify, terminate, or otherwise amend the Subscription Services offered by us, and the contents of the packages and levels thereof.
						
						
						<br>4. Minimum Requirements.   STREAMLY reserves the right to require and/or change minimum programming package requirements, from time to time and at any time, in its sole and absolute discretion. For example, STREAMLY may require a minimum commitment term, or STREAMLY may require you to subscribe to a minimum level or package of our Services in order to view certain content or obtain access to purchase additional levels or packages of Subscription Services. In order to purchase certain packages, you may be required to first subscribe to Services for a set term (e.g., a year-long subscription).
						
						
						<br>5. Promotional Offers.   You may initially accept a promotional offer for a certain package of Services that later becomes unavailable. Promotional offers do not automatically renew and may not be available at the end of, or during, the applicable promotional period, and following the completion of any such promotion, the Subscription Services will automatically renew on a month-to-month basis at the then-current standard rates for monthly access to the same Subscription Services, unless and until they are cancelled or changed by you or STREAMLY, in accordance with these Terms of Use.
						
						
						<br>6. Only One Promotion per Account Owner.   Promotional offers may not be combined with other promotional offers associated with any account that you own, unless otherwise expressly permitted in the applicable Promotional Terms. STREAMLY reserves the right, in its sole and absolute discretion, to determine your eligibility for any promotional offer pursuant to the applicable Promotional Terms. STREAMLY reserves the right to change, amend and alter then-current promotional offers or Promotional Terms as well as institute new promotional offers, from time to time and at any time, in its sole and absolute discretion. We do not guarantee that any promotional offers are or will be available to you at all, or in connection with any Services desired by you.
						
						
						<br>7. Cancellation Policies.   Your Subscription Services are prepaid on a monthly billing period basis and you may contact us to cancel them at any time by visiting www.streamlytv.com/account or by calling Customer Service at: 1 (888)-309-0838. Cancellation requests are not accepted via email. Because charges are prepaid each billing period, when you call to cancel your Subscription Services, your subscription will continue, and you will be able to enjoy your Subscription Services through the end of, the then-current billing period (except as otherwise provided herein to the contrary (see Section 5.D.) or in any Promotional Terms). Refunds are not issued for a partial billing period. WE ARE NOT OBLIGATED, AND YOU ARE NOT ENTITLED AND HEREBY WAIVE ANY RIGHT, TO ANY CREDIT, REFUND, PRICE ADJUSTMENT OR ANY OTHER DISCOUNT, COMPENSATION OR RECOMPENSE FOR ANY PARTIAL-USE, EARLY CANCELATION OR UNWATCHED PRE-PAID SERVICES. When you cancel your Subscription Services, you may be subject to certain charges and fees as set forth in any applicable Promotional Terms, and if you have taken advantage of a promotional offer that required you to pre-pay for a minimum period of time (e.g., 3 months), then your cancellation will not become effective until the end of that minimum period of time, and in such case, for purposes of such promotional offer, your initial “billing period” will mean such minimum period of time. Your cancelation of Subscription Services does not automatically cancel your Membership Account or access to our Transactional Services. If you pay for the Services through your account with a third party (e.g., Amazon) and want to cancel your Membership Account or access to our other Services, then you may need to do so through your account with such third party.
						
						
						<br>Section 5: BILLING
						<br>1. Fees and Charges.   We will bill you monthly in advance for your Subscription Services. Charges are billed to the credit or debit card you registered with your Membership Account. STREAMLY may, from time to time and at any time, change the amount of or basis for determining any fees or charges, and/or institute new fees, surcharges, or other charges as determined by us (e.g., surcharges imposed for transmission of programming content in certain jurisdictions). You agree to pay all amounts billed for our Services, as well as all taxes, fees, and other charges, if any, that are now or may in the future be assessed in connection with any of our Services you access or use, and any other charges due and owing to us. We may apply interest and late fees for any amounts paid following the date when due, and we may terminate or suspend your account for any failure to timely pay any amounts or maintain up-to-date payment method information within your account. State and local taxes or reimbursement charges for gross earnings taxes in some states may apply.
						
						
						<br>2. Registration of Credit or Debit Card.   If you do not already have an account, you will be required to establish an account to access certain of our Services. To access certain Services (e.g., video-on-demand rentals), you will also be required to register a valid credit or debit card to process payments of the associated fees. When you register a credit or debit card for a new account, you authorize STREAMLY to place a pending charge to your credit or debit card to verify your billing address and the validity of your credit or debit card; such pending charges are temporary and will not be converted into an actual charge to you. Pending charges, while pending, will, however, reduce the available amount of credit on your credit card or funds available to your debit card. You are solely responsible for any and all fees charged to your credit or debit card by the issuer, bank, or financial institution, including fees for membership, any overdraft or other insufficient funds, or for exceeding any applicable credit limit. Once your account is authenticated, the credit or debit card that you registered with your account will be charged for each transaction without having to reenter your credit or debit card information. You agree that the issuer of any credit or debit card you registered with your Membership Account will accept these Terms of Use as your authorization and pay all amounts billed in connection with use of your account without STREAMLY’s submitting a signed receipt.
						
						
						<br>3. Billing Cycle.   The fees for all Subscription Services and Transactional Services will be billed in advance. For Subscription Services, we automatically bill you each month on the calendar day corresponding to the date on which you first commenced payment for Subscription Services. Membership Account fees for Subscription Services and Transactional Services are fully earned upon payment. If your paid membership to any Subscription Service began on a day not contained in a given month, then we may bill you on a day in the applicable month or such other day as we deem appropriate (e.g., if you are regularly billed on the 31st, then we may bill you on the 28th or 30th of the calendar months that do not have 31 days). Although we endeavor to bill you as described in this paragraph, we reserve the right to change the timing of our billing as necessary, from time to time, and at any time. We may authorize your payment method in anticipation of membership or service-related charges. As used in these Terms of Use, “billing” shall indicate a charge, debit, or other payment clearance, as applicable, against your registered credit or debit card information. For the purposes of this paragraph, “month” or “monthly” refers to your billing cycle.
						
						
						<br>4. No Returns, Credits or Refunds.   YOU UNDERSTAND AND AGREE THAT PAYMENTS ARE NONREFUNDABLE. WE ARE NOT OBLIGATED, AND YOU ARE NOT ENTITLED AND HEREBY WAIVE ANY RIGHT, TO ANY CREDIT, REFUND, PRICE ADJUSTMENT OR ANY OTHER DISCOUNT, COMPENSATION OR RECOMPENSE FOR ANY PARTIALLY USED OR UNUSED SERVICE (E.G., SUBSCRIPTION SERVICES NOT USED OR TRANSACTIONAL SERVICES NOT DOWNLOADED OR FULLY VIEWED). Because charges are prepaid each billing period, following your cancellation of any of your Subscription Services or Transactional Services, you will continue to have access to any and all of such Services for which you have paid through the end of your then-current billing period, and to certain of our other Services that do not require any payment, including, access to and use of certain portions of our Websites and Apps. Notwithstanding the forgoing, although not required or obligated, we reserve the right to evaluate or elect to provide credits, refunds, price adjustments or other discount, compensation or recompense, from time to time, and at any time, in our sole and absolute discretion; provided that any such elections to offer any such credits, refund, price adjustment or other discount, compensation or recompense in one instance does not entitle you to the same or any such benefit in the future for similar or unrelated instances, nor does it create any obligation whatsoever for us to offer such benefit to you or any other user in connection with any past, present, or future request under any circumstance whatsoever.
						
						
						<br>5. Restarting your Services.   If you do not make timely payment for your Services, we may disconnect, suspend, limit, or terminate your access to such our Services, and in such event, we will be immediately and forever wholly relieved from any and all of our duties and obligations to you under these Terms of Use. If your Membership Account is disconnected for non-payment, or for any other reason whatsoever, then STREAMLY may require that you pay, and you agree to pay, any amount due (regardless of how long outstanding, and including all past due charges and all outstanding balances accrued through the date of such disconnection) before we reconnect your access to any of our Services. We are not obligated to reconnect your access to any of our Services. If your Services are disconnected for non-payment, or for any other reason whatsoever, then you may no longer be eligible, even if you pay to restart your Subscription Services, to receive any remaining credits or promotional pricing that you would have been eligible to receive had your Subscription Services, or any of our other Services, not been disconnected, suspended, limited, or terminated. Unless required by applicable law, deposits will not be held segregated from other funds and will not earn or accrue interest. Promotional pricing is valid only at the time of initial purchase of such promotional Subscription Service or Transactional Service, and we reserve the right to stop any promotion at any time for any reason whatsoever.
						
						
						<br>6. Attorney’s Fees/ Collections.   If we use an attorney or a collection agency to collect any money you owe us, or to assert any other right that we may have against you (e.g., any breach of any agreement you may have with STREAMLY or any of our affiliates), then you hereby agree to pay the reasonable costs of such collection or other action. These costs may include the costs of a collection agency, reasonable attorneys’ fees, and court costs. If you believe you have been billed in error, you must contact our Customer Service immediately, and in no event more than 15 days following the date you receive any bill for which you are seeking correction. Failure to timely notify us of any dispute will constitute your acceptance of the corresponding billed amounts. You must pay undisputed portions of any billing statement when due, or, without limitation to any other rights or remedies available to STREAMLY at law, in equity, under contract (including these Terms of Use), or otherwise, all of which are hereby expressly reserved (e.g., we may elect to suspend or terminate your Subscription Services or any of our Services), permanently or temporarily, in whole or in part. All payments for our Services must be made directly by you to us, unless we authorize otherwise; and except as otherwise authorized, STREAMLY shall have no obligation to provide Services for which payment is made by you to a third party or for which payment is made by a third party on your behalf.
						
						<br>7. Puerto Rico. If you reside in Puerto Rico, please note that the fees for all Subscription Services and Transactional Services will be billed to you by "streamlytv.com" even though you are conducting business with DISH Network Puerto Rico L.L.C.
						
						
						 
						
						<br>Section 6: CUSTOMER SUPPORT SERVICES AND RELATED COMMUNICATIONS
						 
						
						<br>1. Customer Support ServicesS   STREAMLY may elect to provide Membership Account support services or other assistance in connection with your Membership Account (“Customer Service(s)”). The levels, methods, and availability of our Customer Service offered is determined by us, in our sole and absolute discretion; we have no obligation to provide any Customer Services whatsoever in connection with our Services. If we make available or otherwise provide Customer Services, then such Customer Services are considered part of our Services for purposes of these Terms of Use, including any and all restrictions, disclaimers, and limitations herein.
						
						
						<br>2. Communications.   By registering for a Membership Account, you hereby consent to receive electronic communications from us and other Contributors related to such account and our Services. These communications may involve sending emails to the email address you provided during registration, or delivering electronic communications via your Membership Account, and will include notices about your Membership Account (e.g., payment authorizations, password or payment method changes, confirmation e-mails, notices, and other similar or transactional information related to such account); these communications are part of our Services and your relationship with us. You agree that any notice, agreements, disclosures, or other communications that we send to you electronically, as described herein, will satisfy any legal communication requirements (e.g., that such communications be in writing and through an appropriate method). You also consent to receiving other electronic communications from us, such as newsletters about new Services features and content, special offers, promotional announcements, and customer surveys. If you no longer want to receive certain non-transactional communications via email, please contact Customer Service at 1 (888)-309-0838, click on the “Unsubscribe” link contained in any email, or on any other link that indicates that you would like to be removed from future, similar non-transactional communications.
						
						
						 
						
						<br>Section 7: INTELLECTUAL PROPERTY
						 
						
						<br>1. Streamly Property.   The “STREAMLY” Services, including any and all content made available in connection with your access and use of our Services, is protected by domestic and international copyright, trade secret, trademark, and other intellectual property laws. “STREAMLY Television,” “STREAMLYTV” and “STREAMLY” are registered trademarks of STREAMLY Inc., and STREAMLY Inc.. and its affiliates maintains any patent applications, patents and copyrights that apply to our Services.
						
						
						<br>2. End-User License Agreement.   Our Websites, Apps, and other software and technologies are developed by, or for, us and are designed to enable our provision of certain content through Permitted Devices. Our Websites, Apps, and other software and technologies may vary by Permitted Device, operating system, or other medium and the functionalities thereof may also differ among Permitted Devices. BY USING ANY OF OUR SERVICES, YOU ACKNOWLEDGE AND AGREE TO OUR END USER LICENSE AGREEMENT (I.E., OUR EULA) AND TO RECEIVE, WITHOUT FURTHER NOTICE OR PROMPTING, UPDATED VERSIONS OF OUR WEBSITES, APPS, AND OTHER SOFTWARE AND TECHNOLOGIES, INCLUDING RELATED THIRD-PARTY SOFTWARE. IF YOU DO NOT ACCEPT THESE TERMS AND CONDITIONS SET FORTH IN OUR EULA, THEN DO NOT USE ANY OF OUR SERVICES.
						
						
						<br>3. Reporting Claims.   If you have a reasonable belief that your or any other’s protected works have been reproduced or distributed in a way that constitutes copyright infringement, or are otherwise aware of any content available in connection with the Services that may be infringing, violating, or otherwise misappropriating the your rights or such other party’s rights, then please notify us immediately by sending us a written statement to the Notice Address set forth in Section 11.B. below, which written statement must set forth as much detail as is available, including, at a minimum: (i) your name; (ii) your address; (iii) your Membership Account number (if any); and (iv) a clear and detailed statement about the applicable concern of copyright infringement and/or related claim or by clicking on the “DMCA Policy” link at www.streamlytv.com and providing us with notice of your claim and other information as instructed.
						
						<br>4. Warning against Piracy and Infringement.   Receiving any portion of the Services without paying for them, and/or any direct or indirect act or attempted act to engage or assist in any unauthorized interception, reception, display, or other transmission or access of any portion of our Services, is a violation of various U.S. federal and state laws and of these Terms of Use. The penalties for violating applicable laws can include imprisonment and civil damage awards of up to $110,000 per violation. Section 605(e)4 of Title 47 of the United States Code makes it a federal crime to modify your device or our Services to receive encrypted (scrambled) television programming without payment of required subscriptions (e.g., Subscription Services). Conviction can result in a fine of up to $500,000 and imprisonment for five years, or both. Any person who procures any hardware or software that has been so modified is an accessory to that offense and may be punished in the same manner. Investigative authority for violations lies with the Federal Bureau of Investigation. In the event that we learn that you are using any Service in a manner that constitutes infringement of third-party intellectual property rights, including rights granted by U.S. copyright law, your Membership Account will be terminated.
						
						<br>Section 8: DISCLAIMER OF WARRANTIES
						 
						<br>YOU EXPRESSLY UNDERSTAND AND AGREE THAT:
						
						<br>1. YOUR USE OF OUR SERVICES IS AT YOUR SOLE RISK. OUR SERVICES ARE PROVIDED ON AN “AS IS” AND “AS AVAILABLE” BASIS. EXCEPT AS OTHERWISE SPECIFICALLY SET FORTH IN THESE TERMS OF USE, STREAMLY (ON BEHALF OF ITSELF AND THE OTHER CONTRIBUTORS) HEREBY EXPRESSLY DISCLAIMS ALL WARRANTIES OF ANY KIND, WHETHER EXPRESS OR IMPLIED, INCLUDING THE IMPLIED WARRANTIES OF SATISFACTION, ENJOYMENT, QUALITY, TITLE, MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, AND NON-INFRINGEMENT. STREAMLY DOES NOT TAKE ANY RESPONSIBILITY WHATSOEVER OR OTHERWISE WARRANT THE PERFORMANCE OF ANY DEVICE, INCLUDING THE CONTINUING COMPATIBILITY OF THE DEVICE (OR ITS OPERATING SYSTEM) WITH ANY OF OUR SERVICES, OR ANY INTERNET CONNECTION, INCLUDING ITS SPEED, BANDWIDTH OR COMPATIBILITY WITH ANY DEVICE OR STREAMLY SERVICE. FURTHERMORE, WE DO NOT MAKE ANY PROMISES ON BEHALF OF ANY THIRD PARTY, INCLUDING THE OTHER CONTRIBUTORS, AND YOU ACKNOWLEDGE AND AGREE THAT YOU HAVE NOT RELIED ON ANY PROMISES MADE BY US RELATED TO SUCH PARTIES OR THE PRODUCTS AND SERVICES PROVIDED BY THEM. The disclaimers set forth in these Terms of Use expressly apply to any terms hyperlinked and incorporated herein, including any Promotional Terms, the Privacy Policy and the EULA, and including the software contained in our Services and your use of such software. We do not warrant that any issues, errors, or other defects or failures in or related to the software or our Services will be corrected.
						
						<br>2. NEITHER STREAMLY NOR ANY OTHER CONTRIBUTOR MAKES ANY WARRANTY WHATSOEVER THAT: (i) THE SERVICE WILL MEET YOUR REQUIREMENTS OR REASONABLE SATISFACTION; (ii) THE SERVICE WILL ALLOW YOU TO COPY, RECORD, VIEW, SKIP, OR TRANSFER ANY PARTICULAR MEDIA, PROGRAMMING, OR PART THEREOF, OR PROVIDE OR CONTINUE TO PROVIDE ANY OTHER PARTICULAR FUNCTIONALITY; (iii) THE SERVICE WILL PERFORM AT A PARTICULAR SPEED, BANDWIDTH, OR DATA-THROUGHPUT RATE, BE UNINTERRUPTED, TIMELY, SECURE, OR ERROR-FREE; (iv) INFORMATION RELATED TO YOUR ACCOUNT OBTAINED FROM THE USE OF THE SERVICE WILL BE ACCURATE OR RELIABLE; OR (v) THE VARIETY, QUANTITY, OR QUALITY OF ANY MEDIA, PRODUCTS, SERVICES, ACCESSORIES, INFORMATION, OR OTHER MATERIAL RENTED OR OBTAINED BY YOU THROUGH OUR SERVICES WILL MEET YOUR EXPECTATIONS, OR ANY ERRORS IN THE SERVICE WILL BE CORRECTED.
						
						<br>3. STREAMLY MAY RECOMMEND CERTAIN LEVELS OF INTERNET CONNECTION FOR USE WITH CERTAIN OF OUR SERVICES; PROVIDED, HOWEVER, THAT YOU HEREBY AGREE AND UNDERSTAND THAT STREAMLY WILL HAVE NO RESPONSIBILITY WHATSOEVER FOR YOUR INTERNET CONNECTIVITY AND STREAMLY DOES NOT WARRANT OR GUARANTEE THE AVAILABILITY OR PERFORMANCE OF ANY INTERNET CONNECTIVITY, INCLUDING THE SPEED, BANDWIDTH, COMPATIBILITY, SUFFICIENCY, EQUIPMENT, OR DELIVERY OF ANY INTERNET CONNECTION OR THAT ANY CERTAIN INTERNET CONNECTIVITY WILL IMPROVE OUR SERVICE(S). PLEASE BE AWARE THAT CERTAIN OF OUR SERVICES MAY REQUIRE THE USE OF ADDITIONAL DATA, AND THAT ANY INCREASED COSTS, FEES, OR OTHER AMOUNTS DUE TO BE PAID BY YOU AS A RESULT OF ANY INCREASE IN USAGE (E.G., USAGE OF DATA) IN CONNECTION WITH THE SERVICES WILL BE YOUR SOLE RESPONSIBILITY.
						
						<br>4. STREAMLY MAY RECOMMEND OR LIST THE NAMES OF CERTAIN DEVICES PERMITTED FOR USE WITH CERTAIN OF OUR SERVICES, BUT YOU HEREBY AGREE AND UNDERSTAND THAT STREAMLY DOES NOT TAKE ANY RESPONSIBILITY WHATSOEVER FOR AND STREAMLY DOES NOT WARRANT THE PERFORMANCE OF ANY DEVICE OR GUARANTEE THAT SUCH DEVICE (OR THE APPLICABLE OPERATING SYSTEM OR VERSION) WILL BE (OR REMAIN) COMPATIBLE WITH ANY OF OUR SERVICES. YOU MAY ONLY USE OUR SERVICES ON PERMITTED DEVICES.
						
						<br>5. WE HEREBY DISCLAIM ANY, AND YOU AGREE THAT WE HAVE, NO OBLIGATION WHATSOEVER TO REPLACE OR SUPPLEMENT ANY SERVICES, PACKAGE OF SERVICES, OR PORTION OF ANY SERVICE(S) THAT WE CHANGE. WE ARE NOT OBLIGATED, AND YOU ARE NOT ENTITLED AND HEREBY WAIVE ANY RIGHT, TO ANY CREDIT, REFUND, PRICE ADJUSTMENT, OR ANY OTHER DISCOUNT, COMPENSATION OR RECOMPENSE FOR ANY AMOUNT PAID BY YOU TO RECEIVE REPLACEMENT, SUPPLEMENTAL OR ALTERNATE SERVICES, PACKAGES OF SERVICES AND/OR POTION OF SERVICES AS A RESULT OF ANY CHANGE.
						
						
						<br>6. ANY MEDIA, SOFTWARE, OR OTHER MATERIAL OR CONTENT DOWNLOADED, STREAMED OR OTHERWISE OBTAINED THROUGH USE OF OUR SERVICES IS DONE AT YOUR OWN DISCRETION AND RISK. YOU WILL BE SOLELY RESPONSIBLE FOR ANY DAMAGE TO YOUR TELEVISION, RECEIVER, DEVICE OR OTHER HARDWARE, OR LOSS OF DATA THAT RESULTS FROM THE DOWNLOAD, STREAMING OR OBTAINING OF ANY SUCH MEDIA, SOFTWARE OR OTHER MATERIAL OR CONTENT. NEITHER STREAMLY NOR ANY OTHER CONTRIBUTOR ASSUMES ANY RESPONSIBILITY, AND WILL NOT BE LIABLE FOR ANY DAMAGES TO, OR VIRUSES THAT MAY INFECT, CORRUPT, OR OTHERWISE HARM ANY OF YOUR PROPERTY OR THE PROPERTY OF ANY THIRD PARTY, INCLUDING YOUR SOFTWARE, DEVICE OR OTHER HARDWARE.
						
						
						<br>7. NO ADVICE OR INFORMATION, WHETHER ORAL OR WRITTEN, OBTAINED BY YOU FROM STREAMLY OR THE OTHER CONTRIBUTORS, OR VIA YOUR MEMBERSHIP ACCOUNT OR USE OF THE SERVICES WILL CREATE ANY WARRANTY NOT EXPRESSLY STATED IN THESE TERMS OF USE.
						
						
						<br>Section 9: LIMITATION OF LIABILITY
						1. Indirect Damages.   YOU EXPRESSLY UNDERSTAND AND AGREE THAT NEITHER STREAMLY NOR ANY OTHER CONTRIBUTORS WILL BE LIABLE TO YOU FOR ANY INDIRECT OR CONSEQUENTIAL DAMAGES (E.G., INCIDENTAL, SPECIAL, CONSEQUENTIAL, PUNITIVE, EXEMPLARY OR OTHER INDIRECT DAMAGES, DAMAGES FOR LOSS OF PROFITS, GOODWILL, USE, DATA, OR OTHER INTANGIBLE LOSSES EVEN IF STREAMLY OR ANY OF SUCH OTHER CONTRIBUTORS HAVE BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES) ARISING FROM, OR OTHERWISE RESULTING DIRECTLY OR INDIRECTLY FROM: (i) USE OF ANY OF OUR SERVICES; (ii) ACTIONS, OMISSIONS, AND OTHER ACTIVITY UNDER YOUR MEMBERSHIP ACCOUNT (INCLUDING USE NOT AUTHORIZED BY US); (iii) THE PERFORMANCE OR NON-PERFORMANCE OF OUR SERVICES; OR (iv) THE INSTALLATION, MAINTENANCE, REMOVAL, OR TECHNICAL SUPPORT OF OUR SERVICES, EVEN IF SUCH DAMAGES RESULT FROM THE NEGLIGENCE OR GROSS NEGLIGENCE OF STREAMLY OR THE OTHER CONTRIBUTORS.
						
						<br>2. Force Majeure.   NEITHER STREAMLY NOR ANY OTHER CONTRIBUTORS WILL BE LIABLE TO YOU FOR ANY FAILURE TO PERFORM THAT IS CAUSED BY OR OTHERWISE RESULTS FROM ANY ACTS OF GOD, FIRES, EARTHQUAKES, FLOODS, POWER OR TECHNICAL FAILURE, SERVER, INTERNET, SATELLITE OR UPLINK FAILURE, ACTS OF ANY GOVERNMENTAL BODY, OR ANY OTHER CAUSE BEYOND OUR REASONABLE CONTROL.
						
						<br>3. Limitation of Liability.   IN ADDITION TO ANY OTHER LIMITATIONS SET FORTH IN THESE TERMS OF USE, NEITHER STREAMLY NOR THE OTHER CONTRIBUTORS, WILL BE LIABLE FOR ANY ADDITION, DELETION, REARRANGEMENT, ALTERATION, INTERRUPTION, CHANGE AND/OR ELIMINATION IN ANY SERVICES OR FOR ANY DELAY, FOR THE AVOIDANCE OF DOUBT, INCLUDING: (i) IF SUCH ADDITION, DELETION, REARRANGEMENT, ALTERATION, INTERRUPTION, CHANGE AND/OR ELIMINATION, OR DELAY ARISES IN CONNECTION WITH THE TERMINATION OR SUSPENSION OF STREAMLY’S ACCESS TO ALL OR ANY PORTION OF OUR SERVICES FOR ANY REASON WHATSOEVER; (ii) THE RELOCATION OF ALL OR ANY PORTION OF OUR SERVICES TO DIFFERENT SERVER(S); (iii) ANY ADDITION, DELETION, REARRANGEMENT, ALTERATION, INTERRUPTION, CHANGE AND/OR ELIMINATION IN THE FEATURES AND/OR FUNCTIONALITY AVAILABLE WITH YOUR PERMITTED DEVICE(S) OR OUR SERVICES; (iv) ANY SOFTWARE OR OTHER DOWNLOADS INITIATED BY US OR OUR CONTRIBUTORS; OR (v) ISSUES RESULTING FROM YOUR DEVICE OR INTERNET CONNECTION, INCLUDING, LACK OF SUFFICIENT BANDWIDTH. FURTHERMORE, NONE OF STREAMLY, ITS PROVIDERS, DEVICE PROVIDERS OR SERVICE PROVIDERS, OR ANY OF ITS OR THEIR THIRD-PARTY LICENSORS WILL BE LIABLE FOR ANY ADDITION, DELETION, REARRANGEMENT, ALTERATION, INTERRUPTION, CHANGE AND/OR ELIMINATION OF OR TO ANY DEVICE(S), FOR THE AVOIDANCE OF DOUBT, INCLUDING REMOVING OR DISABLING SOFTWARE, FEATURES AND/OR FUNCTIONALITY.
						
						<br>4. Limitation of Direct Damages.   EXCEPT AS SPECIFICALLY AND EXPRESSLY SET FORTH IN SECTION 11, THE MAXIMUM AGGREGATE LIABILITY OF STREAMLY FOR ANY AND ALL CLAIMS UNDER OR RELATING TO EACH: (i) MEMBERSHIP ACCOUNT INCLUDING ALL AUTHORIZED USERS THERE UNDER (IF MORE THAN 1 IS PERMITTED BY US), or (ii) YOU ONLY, IF YOU ARE NOT AN AUTHORIZED USER UNDER ANY MEMBERSHIP ACCOUNT, IS LIMITED TO AN AMOUNT EQUAL TO THE AGGREGATE OF THE FEES THAT WERE PAID BY YOU UNDER THESE TERMS OF USE DURING THE 6 MONTHS BEFORE THE APPLICABLE CLAIM, OR IF DURING THE FIRST 6 MONTHS OF ANY ACCESS OR USAGE TERM, THEN THE FIXED FEES TO BE PAID BY YOU UNDER SUCH TERMS OF USE DURING THE FIRST 6 MONTHS OF SUCH USAGE TERM.
						
						<br>5. Applicability.   SOME JURISDICTIONS DO NOT ALLOW THE EXCLUSION OF CERTAIN WARRANTIES OR THE LIMITATION OR EXCLUSION OF LIABILITY FOR INCIDENTAL OR CONSEQUENTIAL DAMAGES. ACCORDINGLY, SOME OF THE ABOVE LIMITATIONS MAY NOT APPLY TO YOU, BUT ONLY TO THE EXTENT SUCH LIMITATIONS ARE EXPRESSLY DISALLOWED UNDER THE APPLICABLE LAW(S).
						
						<br>Section 10: INDEMNIFICATION
						<br>You hereby agree to indemnify, defend and hold STREAMLY and its parents and affiliates, each of its and their present and future media providers, device providers, business partners, service providers, licensors, contractors, subcontractors, authorized distributors, authorized sub-distributors, authorized retailers, directors, members, managers, officers, agents, employees, and other Users, and each of their respective assigns, heirs, successors, and legal representatives (the “Protected Group”) harmless from, against and with respect to any and all actions, claims, disputes or demands, including reasonable attorneys’ fees and costs, that are incurred in connection with, arising out of or relating to access to or use of our Services by you or in connection with your Membership Account including any User thereof, your connection to our Services, your devices, your violation of these Terms of Use or your violation of any rights of another User.
						
						 
						
						<br>Section 11: DISPUTE RESOLUTION, ARBITRATION AGREEMENT AND CLASS ACTION WAIVER
						<br>1. Mandatory Binding Arbitration and Class Action Waiver.   Subject the provisions of this Section 11 below, if you access or use any of our Services, then you and STREAMLY hereby agree that any and all past, present and future disputes, claims, actions or other controversies arising out of or relating in any way to our Services, these Terms of Use and/or your relationship with the Protected Group except any Dispute relating to the enforcement of STREAMLY’s, its affiliates’ or any of the Protected Group’s intellectual property rights and without limiting our and your rights to resolve disputes using various informal dispute resolutions (e.g., pursuant to Section 5.E. above) (each, a “Dispute”) will be determined exclusively on an individual (non-class) basis by binding arbitration or, within the scope of its jurisdiction, in small claims court. Arbitration is more informal than a lawsuit in court. Arbitration uses a neutral arbitrator instead of a judge or jury, allows for more limited discovery than in court, and is subject to very limited review by courts under the U.S. Federal Arbitration Act (“FAA”). Use of a neutral arbitrator is designed to provide you and us with a fair proceeding. Arbitrators may award the same damages and relief that a court may award, and judgment on any such award may be entered in any court of competent jurisdiction. YOU HEREBY ACKNOWLEDGE YOUR UNDERSTANDING AND AGREE THAT BY ACCEPTING THESE TERMS OF USE AND THE ARBITRATION PROVISIONS HEREIN, THE FAA WILL GOVERN THE INTERPRETATION AND ENFORCEMENT OF THIS PROVISION, AND THAT YOU AND STREAMLY AGREE THAT, BY ENTERING INTO THIS AGREEMENT, YOU AND STREAMLY ARE EACH HEREBY IRREVOCABLY WAIVING THE RIGHT TO A TRIAL BY JURY AND THE RIGHT TO PARTICIPATE IN ANY CLASS OR REPRESENTATIVE ACTION IN CONNECTION WITH ANY DISPUTE BETWEEN US. YOU AND STREAMLY ALSO AGREE THAT ANY ARBITRATION CONDUCTED HEREUNDER WILL BE BROUGHT ONLY ON AN INDIVIDUAL BASIS, AND WILL NOT BE BROUGHT OR PROCEED ON BEHALF OF A CLASS OR IN A REPRESENTATIVE CAPACITY. A “Dispute” includes those based in contract, any applicable current or future federal, state, local or international law, statute, rule, or government or quasi-government order or action or regulation, tort (including fraud, misrepresentation, fraudulent inducement, negligence, willful misconduct or any other intentional tort) or any other legal or equitable theory. The meaning of “Dispute” is to be interpreted to have the broadest possible meaning permitted by law, and will only be modified to the extent necessary to be legal, binding and no longer in conflict with any law as further provided in Section 11.B. below. For example only, Disputes also include your billing disputes, claims involving or relating to telephone calls, direct marketing, advertisements or electronic communications that you claim you received from STREAMLY and/or a party acting on our behalf or any actions or other controversies in connection with our Privacy Policy.
						
						<br>2. Dispute Resolution.   If a Dispute arises and you or we wish to seek arbitration or file any action in small claims court, then the party wishing to seek arbitration or file an action must first send to the other party, by certified mail, notice of the applicable Dispute (“Dispute Notice”), and you and STREAMLY hereby agree to attempt to resolve any such Dispute through informal dispute resolution for at least 60 days following the date on which the applicable Dispute Notice is properly sent. You and we must download or copy the form for your Dispute Notice by clicking here, which form of Dispute Notice must include a written statement that sets forth: (i) the name, address and preferred contact information of the party giving the notice; (ii) a description of the nature and basis of the applicable Dispute; (iii) the facts giving rise to such Dispute; and (iv) the specific relief sought by you in connection therewith. You must send, any and all Dispute Notice(s) to: STREAMLY Inc, at our corporate office (“Notice Address”), by certified mail; and we must send any Dispute Notice to you at the contact information we have for you (e.g., to your Membership Account, the email address you provided to register your Membership Account or your billing address). If we and you do not agree to a resolution of a Dispute during the required informal dispute resolution period above, then either party may commence arbitration or a small claims court proceeding in accordance with this Section 11 to resolve such Dispute. To initiate arbitration proceedings the party seeking such arbitration must open a case by filing with the American Arbitration Association (“AAA”) a demand for such arbitration, paying the administrative filing fee and providing a copy of the applicable arbitration agreement (collectively, the “Demand for Arbitration”); you will only be responsible for paying an arbitration initiation fee equal to the amount that you would pay to file a lawsuit against us in the appropriate court of law in your state, not to exceed $125.00, and we will pay any excess fees or deposit(s) required to initiate such arbitration proceeding. If you are entitled to reimbursement pursuant to this Section 11 and would like us to reimburse you for the actual amount of fees paid by you to initiate an arbitration proceeding, then you must send written notice to STREAMLY at the Notice Address, by certified mail, of: (a) the amount requested; (b) the name of the payee; (c) the return address for the payment; and (d) a copy of the receipt or other documentation evidencing the amount paid by you. You must download or copy a form to initiate arbitration www.adr.org. A Demand for Arbitration must be made through “AAA WebFile,” at www.adr.org, or by filing such demand with any AAA office, regardless of the intended location of the requested proceeding. During the arbitration, the amount of any settlement offer made by us or you may not be disclosed until after the arbitrator determines the amount, if any, to which you or we are entitled in connection with the applicable Dispute.
						
						<br>3. Arbitration Procedures and Forum.   All arbitration proceedings will be administered by the AAA and governed under its Consumer Arbitration Rules (including any amendments thereto or successor rules thereof published by the AAA) (collectively, the “AAA Rules”), as supplemented and modified by these Terms of Use. The AAA Rules are available online at www.adr.org, by calling the AAA at 1 (888)-309-0838, by clicking the hyperlinks above. The arbitrator will be bound by the terms and conditions of these Terms of Use. Subject only to the limited exceptions expressly set forth in this Section 11, the arbitrator, and not any federal, state or local court, agency or other authority, will have the exclusive authority to resolve any and all Disputes, including issues relating to the scope, interpretation, applicability and enforceability of this arbitration agreement (e.g., any claim that all or any part of these Terms of Use (including, for example, this Section 11) is void or voidable). Unless you and STREAMLY agree otherwise, any arbitration hearing will be held in the county (or parish) of your billing address or if no billing address is provided, then of your primary residence.
						
						<br>4. Costs of Arbitration; Compelling Arbitration.   Subject to the exceptions expressly set forth in this Section 11, if you or we decide to initiate arbitration and seek less than $75,000 in damages, then we will pay all costs and fees of the applicable arbitration proceeding for any such proceeding initiated in accordance with the notice requirements unless the arbitrator finds that the arbitration was brought for an improper purpose; and if you or we initiate any arbitration proceeding seeking more than $75,000 in damages, then the payment of such costs and fees will be governed by the applicable AAA Rules. Except as otherwise expressly permitted pursuant to Section 11, if either party attempts to have a Dispute reviewed by a court (e.g., files a judicial or administrative action asserting a claim that is subject to arbitration), and the other party successfully compels arbitration of the applicable Dispute, then the party attempting (or that attempted) to have such Dispute reviewed by such court in violation of these Terms of Use must pay the other party’s costs and expenses incurred in seeking to compel arbitration, including reasonable attorneys’ fees.
						
						<br>5. Class Action Waiver.   NEITHER YOU NOR WE SHALL BE ENTITLED TO JOIN OR CONSOLIDATE CLAIMS BY OR AGAINST OTHER INDIVIDUALS OR ENTITIES, OR ARBITRATE ANY CLAIM AS A REPRESENTATIVE MEMBER OF A CLASS OR IN A PRIVATE ATTORNEY GENERAL CAPACITY IN CONNECTION WITH ANY DISPUTE (AS DEFINED ABOVE). Further, unless both you and STREAMLY specifically agree otherwise in writing, the arbitrator may not consolidate more than one person’s claims, and may not otherwise preside over any form of a representative or class proceeding. The arbitrator may award relief only in favor of the individual party seeking relief and only to the extent necessary to provide relief warranted by that individual party’s individual claim. In the event that this Section 11.E. or any other material provision of this Section 11 is held to be unenforceable, void or voidable, then Section 11 in its entirety will be void, ab initio.
						
						<br>6. Rights to Opt-Out.   If you do not wish to be bound to resolve all Disputes exclusively through arbitration proceedings and small claims courts as set forth in Section 11, then you must notify STREAMLY in writing within 30 days following the date on which you initially accept our then-current terms of use for our Services (as further described in Section 1 above (e.g., upon creating a Membership Account)) by sending written notice to STREAMLY at the Notice Address, by certified mail, of your election to reserve arbitration as a non-exclusive means of resolution of Disputes (“Opt-Out Notice”). You must download or copy a form for your Opt-Out Notice by clicking here, which Opt-Out Notice must include a written statement that sets forth: (i) your name; (ii) your address; (iii) your Membership Account number (if any); and (iv) a clear statement that you do not wish to resolve all Disputes with STREAMLY exclusively through arbitration. Please be aware that your decision to provide us with an Opt-Out Notice will automatically terminate any obligation that STREAMLY would have had to resolve disputes exclusively through arbitration (i.e., any Opt-Out Notice will remove the applicable obligations from both parties), and constitutes a waiver of any financial amounts that would have been paid by us in connection with arbitration proceedings as provided in this Section 11. Your delivery of an Opt-Out Notice to us will not result in our termination of your access or use of our Services, and we will not provide you Services inferior to other Authorized Users, or otherwise adversely affect the Services we provide, as a result of our receipt of an Opt-Out Notice from you. Your right to reserve arbitration as an alternative means of resolution of Disputes rather than an exclusive one is limited; and any notice requesting to opt-out not received within 30 days following the date on which you first accept these Terms of Use will be void, ab initio, and will constitute your understanding and agreement to resolve all Disputes exclusively through arbitration or small claims court proceedings as described in Section 11.
						
						<br>7. Miscellaneous.   Notwithstanding our rights to make future changes to these Terms of Use, if any such future change made by us to the provisions in Section 11 materially limit or alter your then-current rights, then you may reject such changes by sending written notice to STREAMLY at the Notice Address, by certified mail, of your rejection of the applicable changes: (i) within 30 days following the date on which we notify you of such modified Terms of Use; or (ii) in the event we do not provide notice of such future change, then within 30 days following the date on which you become aware of such modified Terms of Use. The dispute resolution, binding arbitration and class action waiver agreements and provisions will survive the expiration or earlier termination of your Membership Account, Subscription Services or other access to or use of our Services. Please be aware that this agreement does not preclude you from bringing issues to the attention of federal, state or local agencies, including the Federal Communications Commission, and such agencies can, if the law allows, seek relief against us on your behalf. We also each agree that this agreement evidences a transaction in interstate commerce, and thus the FAA governs the interpretation and enforcement of the provisions and agreements set forth in this Section 11.
						
						<br>Section 12: MISCELLANEOUS
						<br>1. Notice.   Any notice required or permitted to be given by STREAMLY under these Terms of Use may be provided by any reasonable means, including by mail, by publication on the www.streamlytv.com website, via your Membership Account for our Services (e.g., by sending an electronic message to your Membership Account), by telephone or by e-mail. If we send you notice by mail, it will be considered given the day after it is deposited in the U.S. mail, addressed to you at your physical address of record as then-currently stated in our records. If we send you notice to your personal e-mail or through your Membership Account e-mail, including notice directing you to changes published on our Website, such notice will be considered given upon sending to your e-mail address of record as then-currently stated in our records and associated with your Membership Account or upon sending to your Membership Account. If we give you notice by telephone, it will be considered given when personally delivered to you or when left as a message at your phone number of record as then-currently stated in our records. Unless otherwise specified in these Terms of Use, any notice required or permitted to be given by you under these Terms of Use must be in writing and be sent to STREAMLY at the Notice Address, by certified mail; and will be deemed given only when such notice is received by us at the Notice Address. If your name, contact information or other account information is no longer accurate or otherwise changes, then you must promptly access and correct such information associated with your Membership Account as further described under the “Your Rights” section of our Privacy Policy.
						
						<br>2. Entire Agreement.   These Terms of Use, including those terms hyperlinked and incorporated herein, constitute the entire agreement between STREAMLY and you regarding the subject matter hereof. If you are an existing User of any websites, products or other services provided by our affiliates (e.g., direct broadcast satellite programming services), then your acceptance and agreement to these Terms of Use is independent of, and without modification to, any such other agreement and will not amend, modify or otherwise affect any such relationship governed thereby. With respect to any of our Services, these Terms of Use govern your use of such Services, and supersede any prior agreements or understandings between you and STREAMLY with respect to the subject matter hereof. For clarity, if you access, use or purchase our Services from a third party or through any third-party platform (e.g., Services sold by our business partners) or use any third-party device, then their terms and conditions (or other agreements agreed to by you) governing those devices will govern your use of such device and relationship with such third party.
						
						<br>3. Applicable Law.   These Terms of Use, including all matters relating to their validity, construction, performance and enforcement, and any claim, complaint or dispute arising out of or related to these Terms of Use, your relationship to STREAMLY and/or otherwise related to our Services will be governed by the laws of the State of Nevada without giving effect to its conflict of law provisions. If any provision in these Terms of Use is declared to be illegal, invalid or is otherwise in conflict with any law, then such provision will automatically be considered modified to the minimum extent necessary to make such provision legal, binding and no longer in conflict with such law, without affecting the validity of any other provisions of such Terms of Use, and any and all other provisions will remain in full force and effect to the fullest extent permitted by law.
						
						<br>4. Choice of Forum.   You agree that any action at law, in equity, under contract (including under these Terms of Use) or otherwise that is excluded from, or otherwise not subject to arbitration or small claims court must be filed, and that venue properly lies, only in the state or federal courts located in the city and county of Denver, Colorado, United States of America and you hereby irrevocably consent and submit to the personal jurisdiction of such courts for the purpose of litigating such action.
						
						<br>5. Assignment.   You agree that STREAMLY may assign or otherwise transfer (e.g., sell) your agreement to these Terms of Use with STREAMLY to any third party without your prior consent. You may not assign or otherwise transfer (e.g., via the sale of any Permitted Device) any agreement(s) with us without first obtaining our prior written consent, which consent may be withheld, delayed or conditioned for any reason whatsoever (e.g., your Membership Account reflects an outstanding unpaid balance).
						
						<br>6. No Waiver; Remedies Cumulative.   Except as otherwise expressly provided in these Terms of Use, the failure of a party to exercise or enforce any right or provision of these Terms of Use will not constitute a waiver of such right or provision. The rights and remedies provided under these Terms of Use to STREAMLY in case of your default or breach of these Terms of Use are cumulative and without prejudice to any other rights and remedies that STREAMLY or you may have at law, in equity, under contract (including under these Terms of Use) or otherwise, all of which are hereby expressly reserved.
						
						
						<br>7. No Third-Party Beneficiaries; Claim Limitation.   You agree that, except as otherwise expressly provided in these Terms of Use, there are no third party beneficiaries to your agreement to these Terms of Use. You agree that regardless of any law to the contrary, any claim, Dispute or cause of action by you arising out of or related to our Services pursuant to these Terms of Use must be filed within 1 year following the date on which such claim, Dispute or cause of action arose; and if not filed within such 1-year period, then you hereby irrevocably waive any and all rights to pursue such claim(s), Disputes or other cause(s) of action.
						
						<br>8. Headings.   The section titles and paragraph headings in these Terms of Use are for convenience only and have no legal or contractual effect. Any and all examples or references to “include,” “includes” or “including” set forth in these Terms of Use are by way of example only and will be interpreted as non-limiting. Any reference in these Terms of Use to the singular form of a word will include the plural form of the word, if applicable, and any reference to the plural will include the singular, if applicable. In addition, the term “days” when used in these Terms of Use, unless specified as a ‘business day’ means a calendar day. For the purposes of these Terms of Use, a ‘business day’ means a weekday (other than a Saturday or a Sunday) excluding any national (United States) holiday.
						
						<br>9. Survival.   Any provision of these Terms of Use that logically would be expected to survive the suspension, completion, expiration or earlier termination of your Membership Account, Subscription Services and/or other Services or your agreement to these Terms of Use will survive such suspension, completion, expiration or earlier termination, including any and all representations and warranties contained in these Terms of Use and any and all provisions and agreements regarding indemnification obligations, confidentiality obligations, dispute resolution, binding arbitration and class action waivers, the “Miscellaneous” provisions in this Section 12, limitations of liability and disclaimers.
						
					</p>
				</div>

				<div id="pp" class="col-sm-12 collapse" data-parent="#accordion">
					<h5>Data Collection</h5>
					<h5></h5>STREAMLY Privacy Policy </h5>

					<p>STREAMLY is committed to respecting the privacy of our users. We strive to provide a safe, secure user experience. We have adopted this privacy policy ("Privacy Policy") to explain what information may be collected through our Internet Service, how we use this information, and under what circumstances we may disclose the information to third parties. This Privacy Policy only applies to information we collect through our Service  and does not apply to our collection of information from other sources. </p>
					
					<p>This Privacy Policy, together with the Authorized Use Policy and Terms & Conditions of Service posted on our website, sets forth the general rules and policies governing your use of our Service. When you use our Service  you agree to this Privacy Policy and other listed policies. Information We May Gather About You will be stored and processed in the United States. The information we gather at STREAMLY will not be shared with third parties unless necessary to make improvements to our products and services using consultants and vendors who will be contractually obligated to not further share this information. </p>
					
					<p>STREAMLY’S web-site may contain links to other web-sites over which we have no control. We are not responsible for the privacy policies or practices of other websites to which you choose to link from our sites. We encourage you to review the privacy policies of those other web-sites so that you can understand the privacy of that web-site. </p>
					
					<p>STREAMLY may request that you provide personal information, including your name, address, e-mail address, telephone number, credit card number, social security number, contact information, billing information and any other information from which your identity is discernible. </p>
					
					<p>We also gather or may gather certain information about your use of STREAMLY and any related products and services. There is also information about your computer hardware and software that is or may be collected by us. This information can include without limitation your IP address, MAC address(s), browser type, domain names, access times and referring web-site addresses. Our Use of Your Information Except as expressly set forth in the Privacy Policy, </p>
					
					<p>STREAMLY DOES NOT disclose your personal information to third parties, or your combined personal and demographic information. </p>
					
					<p>STREAMLY uses the information we gather from you or when you visit our website, whether personal, demographic, collective, or technical, for operating and improving our service to you and notifying you of the products and services associated with providing you with telecommunications services that we offer. </p>
					
					<p>STREAMLY may use your contact information to send you e-mail or other communications regarding updates that relate to your Internet or telecommunications service or related products or services. STREAMLY discloses information to companies and individuals we retain to perform functions on our behalf, such as consultants or vendors that help maintain our telephone service. For example, third parties that host our web servers that analyze data or that process credit card payments. These third parties will have access to your personal information as necessary to perform their functions that aid STREAMLY’S ability to provide telephone service, but they may not share that information with any other third party. </p>
					
					<p>STREAMLY discloses information if legally required to do so, if requested to do so by a governmental entity, or if we believe in good faith that such action is necessary to (a) conform to legal requirements or comply with legal process; (b) protect our rights or rights and property of our affiliated companies; (c) prevent a crime or protect national security; or (d) protect the personal safety of users or the public. STREAMLY may disclose and transfer information to a third party who acquires all or a substantial portion of our business, whether such acquisition is by way of merger, consolidation or purchase of all or substantial portion of our assets. </p>
					
					<p>Other Uses and Information IP Addresses: An IP address is a number that is automatically assigned to your computer whenever you are surfing the Internet. Web servers (computers that "serve up" web pages) automatically identify your computer by its IP address. When visitors request pages from our Websites, our servers typically log their IP addresses. We collect IP addresses for purposes of system administration, to report non-personal aggregate information to others, and to track the use of our Website. IP addresses are considered non-personal information and may also be shared as provided above. We reserve the right to use IP addresses and any personally identifiable information to identify a visitor when we feel it is necessary to enforce compliance with our Website rules or to: (a) fulfill a government request; (b) conform with the requirements of the law or legal process; (c) protect or defend our legal rights or property, our Website, or other users; or (d) in an emergency to protect the health and safety of our Website's users or the general public. 
					
					<p>Cookies: "Cookies" are small text files from a website that are stored on your hard drive. These text files make using our Website more convenient by, among other things, saving your passwords and preferences for you. Cookies themselves do not typically contain any personally identifiable information. We may analyze the information derived from these cookies and match this information with data provided by you or another party. If you are concerned about the storage and use of cookies, you may be able to direct your internet browser to notify you and seek approval whenever a cookie is being sent to your hard drive. You may also delete a cookie manually from your hard drive through your internet browser or other programs. Please note, however, that some parts of our Website will not function properly or be available to you if you refuse to accept a cookie or choose to disable the acceptance of cookies. 
					</p>
					<p>Email Communications: If you send us an email with questions or comments, we may use your personally identifiable information to respond to your questions or comments, and we may save your questions or comments for future reference. For security reasons, we do not recommend that you send non-public personal information, such as passwords, social security numbers, or bank account information, to us by email. However, aside from our reply to such an email, it is not our standard practice to send you email unless you request a service or sign up for a feature that involves email communications, it relates to purchases you have made with us (e.g., product updates, customer support, etc.), we are sending you information about our other services, or you consented to being contacted by email for a particular purpose. In certain instances, we may provide you with the option to set your preferences for receiving email communications from us. 
					</p>
					<p>Security Measures STREAMLY has put in place measures designed to secure your personal information from accident, loss and from unauthorized access, use, alteration or disclosure. However, while we try to protect your personal information, we cannot guarantee or warrant the security of any information you disclose or transmit to us online. We are not responsible for the theft, destruction, or inadvertent disclosure of your personally identifiable information. Other Sites You may use our Service  to link or otherwise access third party websites that we do not control or maintain. There may even be links on our website to third party websites. We are not responsible for the privacy practices and policies employed by any third party. You are encouraged to be aware of when you leave STREAMLY’S website. We further encourage you to read the privacy statement of all third-party websites before submitting any personally identifiable information at that website. We may offer chat rooms, blogs, message boards, bulletin boards, or similar public forums where you and other users of our websites can communicate. The protections described in this Privacy Policy do not apply when you provide information (including personal information) in connection with your use of these public forums. We may use personally identifiable information and non-personal information about you to identify you with a posting in a public forum. Any information you share in a public forum is public information and may be seen or collected by anyone, including third parties that do not adhere to our Privacy Policy. We are not responsible for events arising from the distribution of any information you choose to publicly post or share through our Websites. 
					</p>
					<p>Changes.  If STREAMLY decides to materially change our Privacy Policy, we will post those changes through a prominent notice in the company web-site so that you will always know what information is being gathered and how that information might be used. We encourage you to review the Privacy Policy from time to time to be sure you know what the policy provides. You may review, correct, update or change your member information at any time. STREAMLY’S representatives can assist you with that process. 
					</p>
					<p>Contact Us. If at any time, you have questions or concerns about this privacy statement or believe that we have not adhered to this privacy statement, please feel free to contact us via the Contact Us form on the website. STREAMLY’S representatives will promptly answer your question and try to resolve your problem.
					</p>

				</div>
			</div>

			</div>
		</div>
	</section>
	<section></section>
@endsection