<!-- Footer-->
<footer class="page-footer " >
	<div class="container">
		<div class=" text-center text-md-left">
			<div class="row">
				<div class="col-sm-3 ">
					<ul class="social-list">
									<li><img src="{{ asset('frontend/img/facebook.svg') }}" alt="Logo of fb"></li>
									<li><img src="{{ asset('frontend/img/instagram.svg') }}" alt="Logo of instagram"></li>
									<li><img src="{{ asset('frontend/img/twitter.svg') }}" alt="Logo of twitter"></li>
							</ul>
				</div>
		
					<hr class="clearfix w-100 d-md-none">
				<div class="col-sm-2 mx-auto">
					<h6>OUR CHANNELS</h6>
					<ul>
					
						<li><a href="/livetv" style="color:#C5C5C5">Complete List</a></li>
						@foreach($genres as $genre)
						<li><a href="/livetv/{{$genre}}" style="color:#C5C5C5">{{$genre}}</a></li>
						@endforeach
						<li><a href="/livetv/Others" style="color:#C5C5C5">Others</a></li>
						
					</ul>	
				</div>
				
				<hr class="clearfix w-100 d-md-none">
				<div class="col-sm-2 mx-auto">
					<h6>HELP</h6>
					<ul>
						<li><a href="{{ url('/account/settings') }}" style="color:#C5C5C5">Account and Billing</a></li>
						@if( isset($login) && $login)
						<li><a href="{{ url('/selectplan') }}"  style="color:#C5C5C5">Plans and Pricing</a></li>
						@else
						<li><a href="{{ url('/#services') }}"  style="color:#C5C5C5">Plans and Pricing</a></li>
						@endif
						<li><a href="{{ url('/devices') }}"  style="color:#C5C5C5">Supported Devices</a></li>
						<li><a href="{{ url('/account/settings/bug') }}" style="color:#C5C5C5">Report a bug</a></li>
						<!-- <li ><a href="{{ url('accessibility') }}" style="color:#C5C5C5">Accessibility</a></li> -->
						<li ><a href="{{ url('uploads/Accessiblity/streamly accessiblity statement for website.pdf') }}" target="_blank" style="color:#C5C5C5">Accessibility</a></li>
					</ul>
				</div>
				
				<hr class="clearfix w-100 d-md-none">
				<div class="col-sm-2 mx-auto">
					<h6>ABOUT US</h6>
					<ul>
						<li><a href="{{ url('/contactform') }}" style="color:#C5C5C5">Contact us</a></li>
						<li><a href="{{ url('/helpandsupport') }}" style="color:#C5C5C5">Help and support</a></li>
						<li><a href="{{ url('termsofuse') }}" style="color:#C5C5C5">Terms of use</a></li>
						<li><a href="{{ url('privacy') }}" style="color:#C5C5C5">Privacy Policy</a></li>
						<li><a href="{{ url('faq') }}" style="color:#C5C5C5">FAQ</a></li>
					</ul>
				</div>
				
					<!-- <div class="col-lg-4 text-lg-left">Copyright © Your Website 2020</div>
					<div class="col-lg-4 my-3 my-lg-0">
							<a class="btn btn-dark btn-social mx-2" href="#!"><i class="fab fa-twitter"></i></a><a class="btn btn-dark btn-social mx-2" href="#!"><i class="fab fa-facebook-f"></i></a><a class="btn btn-dark btn-social mx-2" href="#!"><i class="fab fa-linkedin-in"></i></a>
					</div>
					<div class="col-lg-4 text-lg-right"><a class="mr-3" href="#!">Privacy Policy</a><a href="#!">Terms of Use</a></div> -->
			</div>
	</div>
		<!-- Copyright -->
		<div class="footer-copyright text-center py-3">© {{Date('Y')}} Streamly, LLC
			
		</div>
		<!-- Copyright -->
	</div>

</footer>
