@extends('frontend/partials/master')

@section('stylesheets-bottom')

@section('content')
<style>
		nav#mainNav {
			background-color: #004E92;
		}
        #requestinfo {
            padding: 10rem;
        }
        .btn-primary {
            background-color: #004E92!important;
            border: none;
            color: #fff!important;
        }
        .btn-primary:hover {
            background-color: #FF712F!important;
        }
        #navbarResponsive {
            display: none!important;
        }
</style>
<section id="requestinfo" class="page-section">
    <div class="container">
        <h1>Request Information</h1>

        @if(session()->has('error'))
        <div class="alert alert-danger alert-dismissible ">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        {{ session()->get('error') }}
        </div>
        @elseif(session()->has('success'))
        <div class="alert alert-success alert-dismissible ">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        {{ session()->get('success') }}
        </div>
        @endif

        <form method="POST" action="managed-iptv/submit_info">
            @csrf <!-- CSRF Protection -->
            
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="tel" name="phone" id="phone" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="dma">DMA</label>
                <input type="text" name="dma" id="dma" class="form-control">
            </div>
            
            <div class="form-group">
                <label for="company_name">Company Name</label>
                <input type="text" name="company_name" id="company_name" class="form-control">
            </div>
            
            <div class="form-group">
                <label for="subscribers">Approximate Current Subscribers</label>
                <input type="number" name="subscribers" id="subscribers" class="form-control" min="0">
            </div>
            
            <button  type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</section>
@endsection
