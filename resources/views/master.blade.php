<!-- master.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Streamly</title>

  <!-- Font Awesome Icons -->
  <link href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
  <!-- IonIcons -->
  <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link href="{{ asset('dist/css/adminlte.min.css') }}" rel="stylesheet">
  
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    @yield('stylesheets')
</head>
<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        @include('partials.header')
        @include('partials.sidebar')
        @yield('content')
    </div>
    <!-- Main Footer -->
<footer class="main-footer">
    <strong>Copyright &copy; 2014-2019 <a href="http://adminlte.io">AdminLTE.io</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 3.0.4
    </div>
  </footer>
<!-- ./wrapper -->
<!-- jQuery -->
<script type="text/javascript" src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>


<!-- Bootstrap -->
<script type="text/javascript" src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE -->
<script type="text/javascript" src="{{ asset('dist/js/adminlte.js') }}"></script>
    @yield('scripts')
</body>
</html>
<style>
.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #007bff;
    border: 1px solid #aaa;
    border-radius: 4px;
    cursor: default;
    float: left;
    margin-right: 5px;
    margin-top: 5px;
    padding: 0 5px;
}
.dotred {
  height: 10px;
  width: 10px;
  background-color: #DB3545;
  border-radius: 50%;
  display: inline-block;
}
.dotgreen {
  height: 10px;
  width: 10px;
  background-color: #27A844;
  border-radius: 50%;
  display: inline-block;
}</style>
