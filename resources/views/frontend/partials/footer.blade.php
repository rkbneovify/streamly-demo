<!-- Footer-->
<footer class="footer py-4">
    <div class="container">
        <div class="row">
        	<div class="col-sm-3">
        		<ul class="social-list">
                    <li><img src="{{ asset('frontend/img/facebook.svg') }}" alt="Logo of fb"></li>
                    <li><img src="{{ asset('frontend/img/instagram.svg') }}" alt="Logo of instagram"></li>
                    <li><img src="{{ asset('frontend/img/twitter.svg') }}" alt="Logo of twitter"></li>
                </ul>
        	</div>
        	<div class="col-sm-2">
        		<h6>OUR CHANNELS</h6>
        		<ul>
        			<li><a href="livetv" style="color:#C5C5C5">Complete List</a></li>
        			<li><a href="livetv" style="color:#C5C5C5">News</a></li>
        			<li><a href="livetv" style="color:#C5C5C5">Sports</a></li>
        			<li><a href="livetv" style="color:#C5C5C5">Movies</a></li>
        			<li><a href="livetv" style="color:#C5C5C5">Kids</a></li>
        		</ul>	
        	</div>
        	<div class="col-sm-2">
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
        			<li>Accessibility</li>
        		</ul>
        	</div>
        	<div class="col-sm-2">
        		<h6>ABOUT US</h6>
        		<ul>
        			<li><a href="{{ url('/contactform') }}" style="color:#C5C5C5">Contact us</a></li>
        			<li><a href="{{ url('/helpandsupport') }}" style="color:#C5C5C5">Help and support</a></li>
        			<li><a href="{{ url('termsofuse') }}" style="color:#C5C5C5">Terms of use</a></li>
        			<li><a href="{{ url('privacy') }}" style="color:#C5C5C5">Privacy Policy</a></li>
        			<li><a href="{{ url('faq') }}" style="color:#C5C5C5">FAQ</a></li>
        		</ul>
        	</div>
        	<div class="col-sm-3 pos-relative">
        		<p class="bottom-0 right-0 align-right pos-absolute">© {{Date('Y')}} Streamly, LLC</p>
        	</div>
            <!-- <div class="col-lg-4 text-lg-left">Copyright © Your Website 2020</div>
            <div class="col-lg-4 my-3 my-lg-0">
                <a class="btn btn-dark btn-social mx-2" href="#!"><i class="fab fa-twitter"></i></a><a class="btn btn-dark btn-social mx-2" href="#!"><i class="fab fa-facebook-f"></i></a><a class="btn btn-dark btn-social mx-2" href="#!"><i class="fab fa-linkedin-in"></i></a>
            </div>
            <div class="col-lg-4 text-lg-right"><a class="mr-3" href="#!">Privacy Policy</a><a href="#!">Terms of Use</a></div> -->
        </div>
    </div>
</footer>
