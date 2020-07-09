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
	</style>

	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<script>
		jQuery(document).ready(function(){
			jQuery('.remove-device').on('click', function(){
				jQuery('#removeDevice').modal('show');
			});

			jQuery('.cancel-subscription').on('click', function(){
				jQuery('#cancelSubscription').modal('show');
			});

			jQuery('.change-password').on('click', function(){
				jQuery('#changePassword').modal('show');
			});
		});
	</script>
	<script type="text/javascript">
		@if (count($errors) > 0)
		jQuery('#changePassword').modal('show');
		@endif
		</script>
@endsection

@section('content')
<section class="page-section custom-row">

@if(session()->has('message'))
<div class="alert alert-success alert-dismissible alert-position">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		{{ session()->get('message') }}
</div>
@endif
@if(session()->has('message'))
<div class="alert alert-danger alert-dismissible alert-position">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		{{ session()->get('message') }}
</div>
@endif
<div class="container">
				<h1>Bug Report Form</h1>
				<p>Use this form to report any bugs you may find. Here is what each of the "bug severity" options mean:</p>
				<ol>
					<li><div id="minor">Minor:</div> These are bugs that don't really affect the game's playability. Things like missing or broken textures would fit into this category.</li>
					<li><div id="moderate">Moderate:</div> The moderate category would include bugs that affect the functionality of some of the game's less important features. For example, a problem with an object the player can pick up would fall under the moderate category.</li>
					<li><div id="major">Major:</div> A major bug affects the playability of the game's more important feature. A door not opening would be an example of a major bug.</li>
					<li><div id="game_breaking">Game-breaking:</div> A game-breaking bug is a glitch that makes the game unplayable, either immediately after launch or during gameplay. Game crashes (especially persistent crashes) or other issues that make the game completely unplayable would fall under this category.</li>
				</ol>
			</div>
			<div class="container">
			 <form method="POST" enctype="multipart/form-data" action="{{ url('sendbugreport') }}">
				{{ csrf_field() }}
					<p>Your email is required.</p>
					<input type="email" id="email_input" name="email_input" placeholder="firstname.lastname@example.com" required>
					<div class="form-group">
						<p>Bug Severity (required):</p>
						<select name="bug_severity" id="bug_severity" required>
							<option value="--Please Select--">--Please Select--</option>
							<option value="Minor">Minor</option>
							<option value="Moderate">Moderate</option>
							<option value="Major">Major</option>
							<option value="Game-breaking">Game-breaking</option>
						</select></div>
					<div class="form-group">
						<p>How often does it occur?</p>
						<select name="occurrence" id="occurrence" required>
							<option value="--Please Select--">--Please Select--</option>
							<option value="Rarely">Rarely</option>
							<option value="Somewhat Rarely">Somewhat Rarely</option>
							<option value="Somewhat Often">Somewhat Often</option>
							<option value="Commonly">Commonly</option>
							<option value="Very Commonly">Very Commonly</option>
						</select></div>
						<div class="row">
<div class="col-md-6 input-group input-group-lg">
	<p>Please input any debug information here:</p>
	<input type="text" name="debug_info" id="debug_info" placeholder="Ex. Exception at 0x0000000 - memory map"></div>
	<div class="col-md-6 input-group input-group-lg">
	<p>Any further questions, comments or issues:</p>
	<input type="text" name="details" id="details" placeholder="Ex. I noticed lagging in some areas."></div></div>

					
					
					
					
					<p>Once you have confirmed everything in your bug report, hit 'submit'. You won't be able to change your bug report later.</p>
					<input type="submit" id="submit" name="submit" value="Submit">
				</form>
			</div>
		</section>
	
@endsection
		
