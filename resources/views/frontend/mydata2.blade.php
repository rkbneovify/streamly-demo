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
     <select id='gracenote_data' style="width: 75%" >
        <option value='0'>- Search gracenote_data -</option>
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

var today = new Date();
var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
//var dateTime2 = date+' '+time;
//console.log('dateTime',dateTime);
//$('#gracenote_data').select2('open');
//var dateTime=new Date().toISOString().slice(0, 19).replace('T', ' ');
  $("#gracenote_data").select2({
    placeholder: 'gracenote_data...',
    // width: '350px',
    allowClear: true,
     closeOnSelect: false,
    ajax: {
        url: '/epgdata',
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return {
                term: params.term || '',
                page: params.page || 1,
                starttime:time,
                callsign:'COMEDYP',
            }
        },
        cache: true
    }, escapeMarkup: function (markup) { return markup; },
    templateResult: function (data) {
       // console.log('templateResult',data);
        return '<span>'+data.StartDateTime+ ' '+ data.text + '</span>';
    }

}).select2('open');


})();

    </script>
  </body>
</html>
