<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
  <div class="container">
  <div class="page-header">
    <h1>select2 with pagination</h1>
    </div>
    <select id='channel_id' style='width: 200px;'>
        <option value='0'>- Search Channel -</option>
     </select>
    </div>
    <script type="text/javascript" src='//ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js'></script>
    <!--<![endif]-->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> <script >

(function() {
 
  $("#channel_id").select2({
    placeholder: 'Channel...',
    // width: '350px',
    allowClear: true,
    ajax: {
        url: '/dataforselect2',
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return {
                term: params.term || '',
                page: params.page || 1
            }
        },
        cache: true
    }
});
})();

    </script>
  </body>
</html>
