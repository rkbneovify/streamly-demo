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
			<h4 class="section-heading text-center">Privacy Policy</h4>
			<div id="pp" class="col-sm-12 " >
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
	</section>
	
@endsection