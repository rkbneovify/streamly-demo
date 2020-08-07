<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>Jump to mult polygon in mapbox</title>
<meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no" />
<script src="https://api.mapbox.com/mapbox-gl-js/v1.11.1/mapbox-gl.js"></script>
<link href="https://api.mapbox.com/mapbox-gl-js/v1.11.1/mapbox-gl.css" rel="stylesheet" />
<script src='https://api.mapbox.com/mapbox.js/plugins/turf/v2.0.2/turf.min.js'></script>
<style>
	body { margin: 0; padding: 0; }
	#map { position: absolute; top: 0; bottom: 0; width: 100%;}
    
</style>
</head>
<body>
    <style>
        #fitBounds {
        display: block;
        position: relative;
    
        margin: 0px auto;
        width: 20%;
        height: 40px;
        padding: 10px;
        border: none;
        border-radius: 3px;
        font-size: 22px;
        text-align: center;
        color: #fff;
        background: #ee8a65;
            cursor: pointer;
        }
        #info {
display: block;
position: relative;
margin: 0px auto;
width: 20%;
/* padding: 10px; */
border: none;
border-radius: 3px;
font-size: 12px;
text-align: center;
color: #222;
background: #fff;
}
        </style>
<div id="map"></div>
<pre id="info"></pre>
<div id='fitBounds' onclick='fly()'>Fly to next location</div>
<script>
    let data ={!! json_encode($geojson, JSON_HEX_TAG) !!};

	mapboxgl.accessToken = 'pk.eyJ1Ijoic2FoaWxrYXNoeWFwNjQiLCJhIjoiY2tkNDFxYmtoMXQxbjJybXlleGxhejE4MSJ9.LGSnbgHFFcuBE8wXHxIMiA';
    var map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/streets-v11',
        center: [-160.4914,55.3192
        ],
        zoom: 10
    });

// Add zoom and rotation controls to the map.
var nav = new mapboxgl.NavigationControl();
map.addControl(nav, 'top-right');

    map.on('load', function() {
        map.addSource('lines', {
            'type': 'geojson',
            'data': data
        });
        map.addLayer({
            'id': 'lines',
            'type': 'line',
            'source': 'lines',
            'paint': {
                'line-width': 3,
                // Use a get expression (https://docs.mapbox.com/mapbox-gl-js/style-spec/#expressions-get)
                // to set the line-color to a feature property value.
                'line-color': ['get', 'color'],
                
            }
        });

   

map.addLayer({
    "id": "clusters-label",
    "type": "symbol",
    "source": "lines",
    "layout": {
      "text-field": "{title}",
      "text-font": [
        "DIN Offc Pro Medium",
        "Arial Unicode MS Bold"
      ],
      "text-size": 12
    }
  });


    });
    map.on('click', function(e) {
document.getElementById('info').innerHTML =
// e.point is the x, y coordinates of the mousemove event relative
// to the top-left corner of the map
JSON.stringify(e.point) +
'<br />' +
// e.lngLat is the longitude, latitude geographical position of the event
JSON.stringify(e.lngLat.wrap());
});
    // This variable will track your current coordinate array's index.
var idx = 0;
// var arrayOfCoordinates = [
//     [-73.554,45.5088],
//     [-73.9808,40.7648],
//     [-117.1628,32.7174],
//     [7.2661,43.7031],
//     [11.374478, 43.846144],
//     [12.631267, 41.85256],
//     [12.3309, 45.4389],
//     [21.9885, 50.0054]
// ];

function getFirstCorrdinatesFromData(result){

    var firstCordinate = [];
      for (var i = 0; i < result.length; i++) {
        //alert(result[i].geometry.type)
        if(result[i].geometry.type == 'MultiPolygon') {
            
            let coordinates = result[i].geometry.coordinates;
            firstCordinate.push(coordinates[0][0][0]);


  }else if(result[i].geometry.type == 'Polygon') {
    let coordinates = result[i].geometry.coordinates;
            firstCordinate.push(coordinates[0][0]);
     
  }else if(result[i].geometry.type == 'Point') {
    let coordinates = result[i].geometry.coordinates;
            firstCordinate.push(coordinates);
     
  } 
      }
      return firstCordinate;
}
var arrayOfCoordinates=getFirstCorrdinatesFromData(data.features);
    function fly() {
         // Back to the first coordinate.
    if (idx >= arrayOfCoordinates.length) {
        idx = 0;
    }

    map.flyTo({
        center: arrayOfCoordinates[idx],essential: true // this animation is considered essential with respect to prefers-reduced-motion

    });

    idx++;


// var coordinates = data.features[0].geometry.coordinates;
// console.log(coordinates[0][0][0]);//firsrt cordinate of multipolygon
// var coordinates = data.features[1].geometry.coordinates;
//   console.log(coordinates[0][0]);//first coordinate of polyogon

}
</script>

</body>
</html>