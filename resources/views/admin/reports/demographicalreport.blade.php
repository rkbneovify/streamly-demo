@extends('master')
@section('content')
<style>
    .canvasjs-chart-credit {
        display: none;
    }
    .content-wrapper {
        background-color: #fff;
    }
    .canvasjsHide {
        padding: 5vw;
        position: absolute;
        background-color: #fff;
        bottom: 0;
    }
    </style>
<?php

    $desktop = $resultDevicetype['desktop'];
    $roku = $resultDevicetype['roku_tv'];
    $apple_tv = $resultDevicetype['apple_tv'];
    $fire_tv = $resultDevicetype['fire_tv'];
    $samsung_tv = $resultDevicetype['samsung_tv'];
    $mobile = $resultDevicetype['mobile'];
    $tablet = $resultDevicetype['tablet'];
    $ipad = $resultDevicetype['ipad'];
    $iphone = $resultDevicetype['iphone'];
    $others = $resultDevicetype['others'];

    $countSessions = $desktop + $roku + $apple_tv + $fire_tv + $samsung_tv + $mobile + $tablet + $ipad + $iphone + $others;

 
$dataPoints = array( 
	array("label"=>"Desktop", "y"=>($desktop/$countSessions) * 100),
	array("label"=>"Roku Tv", "y"=>($roku/$countSessions) * 100),
	array("label"=>"Apple Tv", "y"=>($apple_tv/$countSessions) * 100),
	array("label"=>"Fire Tv", "y"=>($fire_tv/$countSessions) * 100),
	array("label"=>"Samsung Tv", "y"=>($samsung_tv/$countSessions) * 100),
	array("label"=>"Mobile", "y"=>($mobile/$countSessions) * 100),
    array("label"=>"Tablet", "y"=>($tablet/$countSessions) * 100),
    array("label"=>"Ipad", "y"=>($ipad/$countSessions) * 100),
    array("label"=>"Iphone", "y"=>($iphone/$countSessions) * 100),
    array("label"=>"Others", "y"=>($others/$countSessions) * 100)
)
 
?>
<!DOCTYPE HTML>
<html>
<head>
<script>
window.onload = function() {
 
 
var chart = new CanvasJS.Chart("chartContainer", {
    height: 587.438,
	animationEnabled: true,
	title: {
		text: "Usage Share of Devices"
	},
	subtitles: [{
		text: "Active Sessions (<?php echo $countSessions; ?>)"
	}],
	data: [{
		type: "pie",
		yValueFormatString: "#,##0.00\"%\"",
		indexLabel: "{label} ({y})",
		dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
	}]
});
chart.render();
 
}
</script>
</head>
<body>
<div class="content-wrapper">
<div id="chartContainer" style="height: 100%; width: 100%; margin-top: 1vw;">
</div>
<div class="canvasjsHide"></div>
</div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js">
</script>
</body>
</html>

@endsection