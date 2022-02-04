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
				
		}

		#pp h5{
			color: #5E5E5E;
		}
#pp>ul>li{
	list-style-type: none;
	padding-bottom: 20px;
}
		a.card-link {
		    color: #5E5E5E;
		}
		#pp>ul>li>span.rect	{ width: 5px;
height: 28px;
				display: inline-block;
				vertical-align: middle;
				background-color: #000000;
				margin-right: 15px;}
		#pp>ul>li>span.number	{
				margin-right: 83px;
				margin-left: 15px;
				font-weight: bold;
			}

	</style>
@endsection

@section('content')


	<section class="page-section custom-row">

		<div class="container" style="margin-top: 4%;">
			<h4 class="section-heading text-center">Accessibility</h4>
			<div id="pp" class="col-sm-12 " >
<ul>
	<li ><span class="rect"></span><span class="number">1.2.6</span> Sign Language (Prerecorded): Sign language interpretation is provided for all prerecorded audio content in synchronized media. (Level AAA)</li>
	<li><span class="rect"></span><span class="number">1.2.7</span> Extended Audio Description (Prerecorded): Where pauses in foreground audio are insufficient to allow audio descriptions to convey the sense of the video, extended audio description is provided for all prerecorded video content in synchronized media. (Level AAA)</li>
	<li><span class="rect"></span><span class="number">1.2.8</span> Media Alternative (Prerecorded): An alternative for time-based media is provided for all prerecorded synchronized media and for all prerecorded video-only media. (Level AAA)</li>
	<li><span class="rect"></span><span class="number">1.2.9</span> Audio-only (Live): An alternative for time-based media that presents equivalent information for live audio-only content is provided. (Level AAA)</li>
	<li><span class="rect"></span><span class="number">1.4.6</span> Contrast (Enhanced): The visual presentation of text and images of text has a contrast ratio of at least 7:1, except for the following: (Level AAA)</li>
<p>> Large Text: Large-scale text and images of large-scale text have a contrast ratio of at least 4.5:1;<br> > Incidental: Text or images of text that are part of an inactive user interface component, that are pure decoration, that are not visible to anyone, or that are part of a picture that contains significant other visual content, have no contrast requirement. <br> > Logotypes: Text that is part of a logo or brand name has no minimum contrast requirement.</p>
<li><span class="rect"></span><span class="number">1.4.7</span> Low or No Background Audio: For prerecorded audio-only content that (1) contains primarily speech in the foreground, (2) is not an audio CAPTCHA or audio logo, and (3) is not vocalization intended to be primarily musical expression such as singing or rapping, at least one of the following is true: (Level AAA)</li>
<p>> No Background: The audio does not contain background sounds.<br> > Turn Off: The background sounds can be turned off. <br>> 20 dB: The background sounds are at least 20 decibels lower than the foreground speech content, with the exception of occasional sounds that last for only one or two seconds. <br><strong>Note: Per the definition of ""decibel,"" background sound that meets this requirement will be approximately four times quieter than the foreground speech content.</strong></p>
<li><span class="rect"></span><span class="number">1.4.8</span> Visual Presentation: For the visual presentation of blocks of text, a mechanism is available to achieve the following: (Level AAA)</li>
<p>> Foreground and background colors can be selected by the user.<br> > Width is no more than 80 characters or glyphs (40 if CJK). <br>> Text is not justified (aligned to both the left and the right margins).<br> > Line spacing (leading) is at least space-and-a-half within paragraphs, and paragraph spacing is at least 1.5 times larger than the line spacing. <br>> Text can be resized without assistive technology up to 200 percent in a way that does not require the user to scroll horizontally to read a line of text on a full-screen window.</p>
<li><span class="rect"></span><span class="number">1.4.9</span> Images of Text (No Exception): Images of text are only used for pure decoration or where a particular presentation of text is essential to the information being conveyed. (Level AAA) <br><strong>Note: Logotypes (text that is part of a logo or brand name) are considered essential.</strong></li>
<li><span class="rect"></span><span class="number">2.1.3</span> Keyboard (No Exception): All functionality of the content is operable through a keyboard interface without requiring specific timings for individual keystrokes. (Level AAA)</li>
<li><span class="rect"></span><span class="number">2.2.3</span> No Timing: Timing is not an essential part of the event or activity presented by the content, except for non-interactive synchronized media and real-time events. (Level AAA)</li>
<li><span class="rect"></span><span class="number">2.2.4</span> Interruptions: Interruptions can be postponed or suppressed by the user, except interruptions involving an emergency. (Level AAA)</li>
<li><span class="rect"></span><span class="number">2.2.5</span> Re-authenticating: When an authenticated session expires, the user can continue the activity without loss of data after re-authenticating. (Level AAA)</li>
<li><span class="rect"></span><span class="number">2.3.2</span> Three Flashes: Web pages do not contain anything that flashes more than three times in any one second period. (Level AAA)</li>
<li><span class="rect"></span><span class="number">2.4.8</span> Location: Information about the user's location within a set of Web pages is available. (Level AAA)</li>
<li><span class="rect"></span><span class="number">2.4.9</span> Link Purpose (Link Only): A mechanism is available to allow the purpose of each link to be identified from link text alone, except where the purpose of the link would be ambiguous to users in general. (Level AAA)</li>
<li><span class="rect"></span><span class="number">2.4.10</span> Section Headings: Section headings are used to organize the content. (Level AAA)<strong> Note 1: "Heading" is used in its general sense and includes titles and other ways to add a heading to different types of content. </strong><br><strong> Note 2: This success criterion covers sections within writing, not user interface components. User Interface components are covered under Success Criterion 4.1.2.</strong></li>
<li><span class="rect"></span><span class="number">3.1.1</span>Unusual Words: A mechanism is available for identifying specific definitions of words or phrases used in an unusual or restricted way, including idioms and jargon. (Level AAA)</li>
<li><span class="rect"></span><span class="number">3.1.4</span>Abbreviations: A mechanism for identifying the expanded form or meaning of abbreviations is available. (Level AAA)</li>
<li><span class="rect"></span><span class="number">3.1.5</span>Reading Level: When text requires reading ability more advanced than the lower secondary education level after removal of proper names and titles, supplemental content, or a version that does not require reading ability more advanced than the lower secondary education level, is available. (Level AAA)</li>
<li><span class="rect"></span><span class="number">3.1.6</span>Pronunciation: A mechanism is available for identifying specific pronunciation of words where meaning of the words, in context, is ambiguous without knowing the pronunciation. (Level AAA)</li>
<li><span class="rect"></span><span class="number">3.2.5</span>Change on Request: Changes of context are initiated only by user request or a mechanism is available to turn off such changes. (Level AAA)</li>
<li><span class="rect"></span><span class="number">3.2.5</span>Help: Context-sensitive help is available. (Level AAA)</li>
<li><span class="rect"></span><span class="number">3.2.5</span>"Error Prevention (All): For Web pages that require the user to submit information, at least one of the following is true: (Level AAA) <br>> Reversible: Submissions are reversible. <br>> Checked: Data entered by the user is checked for input errors and the user is provided an opportunity to correct them. <br>> Confirmed: A mechanism is available for reviewing, confirming, and correcting information before finalizing the submission."</li>
</ul>
			</div>
				

			
			


		</div>
	</section>
	
@endsection