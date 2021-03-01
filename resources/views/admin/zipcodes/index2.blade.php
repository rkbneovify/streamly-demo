<!DOCTYPE html>
<html>
  <head>
    <title>Datatables AJAX pagination with Search and Sort - Laravel</title>

    <!-- Meta -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
   
    <!-- CSS -->
<link href="{{ asset('dist/css/jquery.dataTables.min.css') }}" rel="stylesheet">

    <!-- Script -->
<script  type="text/javascript" src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dist/js/jquery.dataTables.min.js') }}"></script>   


    <!-- Datatables CSS CDN -->
    <!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css"> -->

    <!-- jQuery CDN -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->

    <!-- Datatables JS CDN -->
    <!-- <script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script> -->

  </head>
  <body>

    <table id='empTable' width='100%' border="1" style='border-collapse: collapse;'>
      <thead>
        <tr>
          <td>S.no</td>
          <td>city</td>
          <td>zipcode</td>
          <td>state</td>
          <td>status</td>
        </tr>
      </thead>
    </table>

    <!-- Script -->
    <script type="text/javascript">
    $(document).ready(function(){

      // DataTable
      $('#empTable').DataTable({
         processing: true,
         serverSide: true,
         ajax: "{{url('admin/zipcode/category2') }}",
         columns: [
            { data: 'id' },
            { data: 'city' },
            { data: 'zipcode' },
            { data: 'state' },
            { data: 'status' },
         ]
      });

    });
    </script>
  </body>
</html>
