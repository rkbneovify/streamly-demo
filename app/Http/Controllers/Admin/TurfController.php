<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Zipcode;
use Illuminate\Support\Facades\DB;
 use JWTAuth;
use App\Models\OutsideLocationPlay;

use Carbon\Carbon;
class TurfController extends Controller
{ 

    /**
 * Wraps a GeoJSON {@link Geometry} in a GeoJSON {@link Feature}.
 *
 * @name feature
 * @param {Geometry} geometry input geometry
 * @param {Object} properties properties
 * @returns {FeatureCollection} a FeatureCollection of input features
 * @example
 * var geometry = {
 *      "type": "Point",
 *      "coordinates": [
 *        67.5,
 *        32.84267363195431
 *      ]
 *    }
 *
 * var feature = turf.feature(geometry);
 *
 * //=feature
 */
function feature( $geometry, $properties ) {
	return [
		'type' => 'Feature',
		'properties' => $properties || [],
		'geometry' => $geometry
	];
}


/**
 * Takes coordinates and properties (optional) and returns a new {@link Point} feature.
 *
 * @name point
 * @param {number[]} coordinates longitude, latitude position (each in decimal degrees)
 * @param {Object=} properties an Object that is used as the {@link Feature}'s
 * properties
 * @returns {Feature<Point>} a Point feature
 * @example
 * var pt1 = turf.point([-75.343, 39.984]);
 *
 * //=pt1
 */

public function __construct()
    {
        $this->middleware('subscribed')->only('index');
    }

function point( $coordinates, $properties ) {
	if ( !is_array( $coordinates ) ) { throw new \Exception( 'Coordinates must be an array' );  }
	if ( count( $coordinates ) < 2 ) { throw new \Exception( 'Coordinates must be at least 2 numbers long' );  }
	return $this->feature( [
			'type' => 'Point',
			'coordinates' => array_slice( $coordinates, 0 )
		], $properties
	);
}
public function index(Request $request)
    {     
        $LAT= $request['latitude'];
				$LON = $request['longitude'];
				if(!is_coordinate($LAT, $LON)){
					$response = array('success' => false,'response_code'=>400,'message'=>'coordinates must only contain numbers or invalid coordinates');
					return response()->json($response, 400);}
			
$point = $this->point([$LON, $LAT],''); 

$data = $this->data();

// $point = $this->featureEach($data,);
$answer=$this->PointwithinFeatureCollection($point,$request);

if($answer['response_code']==403){
	return response()->json($answer, 403);
}else{
return response()->json($answer, 200);}
 }



/**
 * Callback for featureEach
 *
 * @callback featureEachCallback
 * @param {Feature<any>} currentFeature The current Feature being processed.
 * @param {number} featureIndex The current index of the Feature being processed.
 */

/**
 * Iterate over features in any GeoJSON object, similar to
 * Array.forEach.
 *
 * @name featureEach
 * @param {FeatureCollection|Feature|Geometry} geojson any GeoJSON object
 * @param {Function} callback a method that takes (currentFeature, featureIndex)
 * @example
 * var features = turf.featureCollection([
 *   turf.point([26, 37], {foo: 'bar'}),
 *   turf.point([36, 53], {hello: 'world'})
 * ]);
 *
 * turf.featureEach(features, function (currentFeature, featureIndex) {
 *   //=currentFeature
 *   //=featureIndex
 * });
 */
function featureEach( $geojson, $callback ) {
	if ( $geojson['type'] === 'Feature' ) {
		$callback( $geojson, 0 );
	} elseif ( $geojson['type'] === 'FeatureCollection' ) {
		for ( $i = 0;  $i < count( $geojson['features'] );  $i++ ) {
			$callback( $geojson['features'][ $i ], $i );
		}
	}
}

function data(){
	$data = Zipcode::select('zipcodes.*',DB::raw("ST_AsGeoJSON(map.`SHAPE`) shape"))->where('zipcodes.status',1)->join('map', 'zipcodes.zipcode', '=', 'map.zcta5ce10')->get();
        # Build GeoJSON feature collection array
    $geojson = array(
        'type'      => 'FeatureCollection',
        
        // 'crs' => htmlspecialchars(json_encode($crs), ENT_QUOTES, 'UTF-8'),
        'features'  => array()
     );
        if($data->isEmpty()){
            $response = [
                'success' => false,
                'data' => $data,
                'message'=>'No zipcode is allowed',
                'response_code'=>200,
            ];
            return response()->json($response, 200); 
        }
        
          foreach($data as $fieldnam) {
            $properties =  array(
                'color'=> 'red',
                'title' => $fieldnam['zipcode']
                
            );
            
            $feature = array(
                'type' => 'Feature',
                'properties' => $properties,
                
                'geometry' => $fieldnam->shape,
                 
           );
                            
      
        
             array_push($geojson['features'], $feature);
				 }
				 
				 return $geojson;

}

function PointwithinFeatureCollection($point,$request){
	$mydata = $this->data();
	global $zip;
	$ptsWithin = null; $found = false;  $zip='';
	
	$this->featureEach( $mydata, function ( $currentFeature, $featureIndex ) use ( &$turf, $point,$zip,$ptsWithin) {
		
		global $zip;
		$geom = $this->getType( $currentFeature,'' ); 
		
		if ( $geom == 'MultiPolygon' ) {

			$multiPolygon = $this->multiPolygon( $currentFeature['geometry']['coordinates'],'' );

			$ptsWithin = $this->booleanPointInPolygon( $point, $multiPolygon );
			
			if ( $ptsWithin===true ) {

				$zip = $currentFeature['properties']['title'];
				
				
			}


		} else if ( $geom == 'Polygon' ) {
			$polygon = $this->polygon( $currentFeature['geometry']['coordinates'],'','' );
		
			$found = $this->booleanPointInPolygon( $point, $polygon );
		
			if ( $found ===true) {
				
				
				$zip = $currentFeature['properties']['title'];
							
			}
		}
		}
);
if (empty ( $zip ) ) {

	$LAT= $request['latitude'];
	$LON = $request['longitude'];
	$checkpostcode=reverseAddressLookup($LAT,$LON) ; 
	// dd($checkpostcode['address']['postcode']);
	$postcode=$checkpostcode['address']['postcode'];
	$address= $checkpostcode['display_name'];
	$uid=JWTAuth::user()->id;
	// dd($user);
	$last = OutsideLocationPlay::select('zipcode')->where([
		['zipcode', '=', $postcode],
		['users_id', '=', $uid],
		 ])->get()->first();
	
	if(!is_null($last)){
		$now=Carbon::now();
	$deviceDetails	=getDeviceIdforAPI($request);
        $outsideLocation = OutsideLocationPlay::create([
            'users_id' => $uid,
            'zipcode'=>$postcode??'00000',
            'longitude'=>$LON,
            'latitude'=>$LAT,
            'address'=>$address??'Unknown',
            'device_info'=>$deviceDetails,
            'timestamp'=>$now
        ]);
		return [
		'success' => false,
		'message' => 'Not within zipcode boundary',
		'response_code' => 403,'popupmsg'=>'You are away from your home zip code:  Click here to continue viewing.',
		'popupmsgtoggle'=>false
	];} else{
	return [
		'success' => false,
		'message' => 'Not within zipcode boundary',
		'response_code' => 403,
		'popupmsg'=>'You are away from your home zip code:  Click here to continue viewing.','popupmsgtoggle'=>true
	];}
} else {
	return [
		'success' => true,
		'data' => $zip,
		'message' => $zip . ' Zipcode is allowed.',
		'response_code' => 200
	];

}
}

/**
 * Get GeoJSON object's type, Geometry type is prioritize.
 *
 * @param {GeoJSON} geojson GeoJSON object
 * @param {string} [name="geojson"] name of the variable to display in error message
 * @returns {string} GeoJSON type
 * @example
 * var point = {
 *   "type": "Feature",
 *   "properties": {},
 *   "geometry": {
 *     "type": "Point",
 *     "coordinates": [110, 40]
 *   }
 * }
 * var geom = turf.getType(point)
 * //="Point"
 */
function getType( $geojson, $name ) {
	if ( $geojson['type'] === 'FeatureCollection' ) {
		return 'FeatureCollection';
	}
	if ( $geojson['type'] === 'GeometryCollection' ) {
		return 'GeometryCollection';
	}
	if ( $geojson['type'] === 'Feature' && $geojson['geometry'] !== null ) {
		return $geojson['geometry']['type'];
	}
	return $geojson['type'];
}


/**
 * Creates a {@link Feature<MultiPolygon>} based on a
 * coordinate array. Properties can be added optionally.
 *
 * @name multiPolygon
 * @param {Array<Array<Array<Array<number>>>>} coordinates an array of Polygons
 * @param {Object=} properties an Object of key-value pairs to add as properties
 * @returns {Feature<MultiPolygon>} a multipolygon feature
 * @throws {\Exception} if no coordinates are passed
 * @example
 * var multiPoly = turf.multiPolygon([[[[0,0],[0,10],[10,10],[10,0],[0,0]]]);
 *
 * //=multiPoly
 *
 */
function multiPolygon( $coordinates, $properties ) {
	if ( !$coordinates ) {
		throw new \Exception( 'No coordinates passed' );
	}
	return $this->feature( [
			'type' => 'MultiPolygon',
			'coordinates' => $coordinates
		], $properties
	);
}




/**
 * Creates a {@link Polygon} {@link Feature} from an Array of LinearRings.
 *
 * @name polygon
 * @param {Array<Array<Array<number>>>} coordinates an array of LinearRings
 * @param {Object} [properties={}] an Object of key-value pairs to add as properties
 * @param {Object} [options={}] Optional Parameters
 * @param {Array<number>} [options.bbox] Bounding Box Array [west, south, east, north] associated with the Feature
 * @param {string|number} [options.id] Identifier associated with the Feature
 * @returns {Feature<Polygon>} Polygon Feature
 * @example
 * var polygon = turf.polygon([[[-5, 52], [-4, 56], [-2, 51], [-7, 54], [-5, 52]]], { name: 'poly1' });
 *
 * //=polygon
 */
function polygon( $coordinates, $properties, $options ) {
	if ( !$coordinates ) { throw new \Exception( 'coordinates is required' );  }

	for ( $i = 0;  $i < count( $coordinates );  $i++ ) {
		$ring = $coordinates[ $i ];
		if ( count( $ring ) < 4 ) {
			throw new \Exception( 'Each LinearRing of a Polygon must have 4 or more Positions.' );
		}
		for ( $j = 0;  $j < count( $ring[ count( $ring ) - 1 ] );  $j++ ) {
			// Check if first point of Polygon contains two numbers
			if ( $i === 0 && $j === 0 && !$this->isNumber( $ring[ 0 ][ 0 ] ) || !$this->isNumber( $ring[ 0 ][ 1 ] ) ) { throw new \Exception( 'coordinates must contain numbers' );  }
			if ( $ring[ count( $ring ) - 1 ][ $j ] !== $ring[ 0 ][ $j ] ) {
				throw new \Exception( 'First and last Position are not equivalent.' );
			}
		}
	}

	return $this->feature( [
			'type' => 'Polygon',
			'coordinates' => $coordinates
		], $properties, $options
	);
}

function isNumber( $num ) {
	return is_numeric($num) && !is_nan(floatval($num));
}

/**
 * Takes a {@link Point} and a {@link Polygon} or {@link MultiPolygon} and determines if the point resides inside the polygon. The polygon can
 * be convex or concave. The function accounts for holes.
 *
 * @name booleanPointInPolygon
 * @param {Coord} point input point
 * @param {Feature<Polygon|MultiPolygon>} polygon input polygon or multipolygon
 * @param {Object} [options={}] Optional parameters
 * @param {boolean} [options.ignoreBoundary=false] True if polygon boundary should be ignored when determining if the point is inside the polygon otherwise false.
 * @returns {boolean} `true` if the Point is inside the Polygon; `false` if the Point is not inside the Polygon
 * @example
 * var pt = turf.point([-77, 44]);
 * var poly = turf.polygon([[
 *   [-81, 41],
 *   [-81, 47],
 *   [-72, 47],
 *   [-72, 41],
 *   [-81, 41]
 * ]]);
 *
 * turf.booleanPointInPolygon(pt, poly);
 * //= true
 */
function booleanPointInPolygon( $point, $polygon ) {
	// Optional parameters
	// $options = $options || [];
	// if ( gettype( $options ) !== 'object' ) { throw new \Exception( 'options is invalid' );  }
	$ignoreBoundary = true;

	// validation
	if ( !$point ) { throw new \Exception( 'point is required' );  }
	if ( !$polygon ) { throw new \Exception( 'polygon is required' );  }
// echo "from booleanpointInpolygon";
// print_r($point);
	$pt = $this->getCoord( $point );
	$polys = $this->getCoords( $polygon );
	$type = ( $polygon['geometry'] ) ? $polygon['geometry']['type'] : $polygon['type'];
	if(array_key_exists('bbox', $polygon)) {
    $bbox = $polygon['bbox'];

	// Quick elimination if point is not inside bbox
	if ( $bbox && inBBox( $pt, $bbox ) === false ) { return false;  }
}
	

	// normalize to multipolygon
	if ( $type === 'Polygon' ) { $polys = [ $polys ];  }

	for ( $i = 0,  $insidePoly = false;  $i < count( $polys ) && !$insidePoly;  $i++ ) {
		// check if it is in the outer ring first
		if ( $this->inRing( $pt, $polys[ $i ][ 0 ], $ignoreBoundary ) ) {
			$inHole = false;
			$k = 1;
			// check for the point in any of the holes
			while ( $k < count( $polys[ $i ] ) && !$inHole ) {
				if ( $this->inRing( $pt, $polys[ $i ][ $k ], !$ignoreBoundary ) ) {
					$inHole = true;
				}
				$k++;
			}
			if ( !$inHole ) { $insidePoly = true;  }
		}
	}
	return $insidePoly;
}


/**
 * Unwrap a coordinate from a Point Feature, Geometry or a single coordinate.
 *
 * @name getCoord
 * @param {Array<number>|Geometry<Point>|Feature<Point>} obj Object
 * @returns {Array<number>} coordinates
 * @example
 * var pt = turf.point([10, 10]);
 *
 * var coord = turf.getCoord(pt);
 * //= [10, 10]
 */
function getCoord( $obj ) {
	if ( !$obj ) { throw new \Exception( 'obj is required' );  }

	$coordinates = $this->getCoords( $obj );

	// getCoord() must contain at least two numbers (Point)
	if ( count( $coordinates ) > 1 && $this->isNumber( $coordinates[ 0 ] ) && $this->isNumber( $coordinates[ 1 ] ) ) {
		return $coordinates;
	} else {
		throw new \Exception( 'Coordinate is not a valid Point' );
	}
}

/**
 * Unwrap coordinates from a Feature, Geometry Object or an Array of numbers
 *
 * @name getCoords
 * @param {Array<number>|Geometry|Feature} obj Object
 * @returns {Array<number>} coordinates
 * @example
 * var poly = turf.polygon([[[119.32, -8.7], [119.55, -8.69], [119.51, -8.54], [119.32, -8.7]]]);
 *
 * var coord = turf.getCoords(poly);
 * //= [[[119.32, -8.7], [119.55, -8.69], [119.51, -8.54], [119.32, -8.7]]]
 */
function getCoords( $obj ) {
	if ( !$obj ) { throw new \Exception( 'obj is required' );  }
	$coordinates = null;
if ( $obj['geometry'] && $obj['geometry']['coordinates'] ) {
		$coordinates = $obj['geometry']['coordinates'];
	}
	// Checks if coordinates contains a number
	if ( $coordinates ) {
		$this->containsNumber( $coordinates );
		return $coordinates;
	}
	throw new \Exception( 'No valid coordinates' );
}

/**
 * Checks if coordinates contains a number
 *
 * @name containsNumber
 * @param {Array<any>} coordinates GeoJSON Coordinates
 * @returns {boolean} true if Array contains a number
 */
function containsNumber( $coordinates ) {
	
	if ( count( $coordinates ) > 1 && $this->isNumber( $coordinates[ 0 ] ) && $this->isNumber( $coordinates[ 1 ] ) ) {
		return true;
	}

	if ( is_array( $coordinates[ 0 ] ) && count( $coordinates[ 0 ] ) ) {
		return $this->containsNumber( $coordinates[ 0 ] );
	}
	throw new \Exception( 'coordinates must only contain numbers' );
}
function getGeom( $geojson ) {
	if ( $geojson['type'] === 'Feature' ) {
		return $geojson['geometry'];
	}
	return $geojson;
}

/**
 * inBBox
 *
 * @private
 * @param {Position} pt point [x,y]
 * @param {BBox} bbox BBox [west, south, east, north]
 * @returns {boolean} true/false if point is inside BBox
 */
function inBBox( $pt, $bbox ) {
	return $bbox[ 0 ] <= $pt[ 0 ]
&&		$bbox[ 1 ] <= $pt[ 1 ]
&&		$bbox[ 2 ] >= $pt[ 0 ]
&&		$bbox[ 3 ] >= $pt[ 1 ];
}

/**
 * inRing
 *
 * @private
 * @param {Array<number>} pt [x,y]
 * @param {Array<Array<number>>} ring [[x,y], [x,y],..]
 * @param {boolean} ignoreBoundary ignoreBoundary
 * @returns {boolean} inRing
 */
function inRing( $pt, $ring, $ignoreBoundary ) {
	$isInside = false;
	if ( $ring[ 0 ][ 0 ] === $ring[ count( $ring ) - 1 ][ 0 ] && $ring[ 0 ][ 1 ] === $ring[ count( $ring ) - 1 ][ 1 ] ) {
		$ring = array_slice( $ring, 0, count( $ring ) - 1/*CHECK THIS*/ );
	}
	for ( $i = 0,  $j = count( $ring ) - 1;  $i < count( $ring );  $j = $i++ ) {
		$xi = $ring[ $i ][ 0 ];
		$yi = $ring[ $i ][ 1 ];
		$xj = $ring[ $j ][ 0 ];
		$yj = $ring[ $j ][ 1 ];
		$onBoundary = ( $pt[ 1 ] * ( $xi - $xj ) + $yi * ( $xj - $pt[ 0 ] ) + $yj * ( $pt[ 0 ] - $xi ) === 0 )
&&			( ( $xi - $pt[ 0 ] ) * ( $xj - $pt[ 0 ] ) <= 0 ) && ( ( $yi - $pt[ 1 ] ) * ( $yj - $pt[ 1 ] ) <= 0 );
		if ( $onBoundary ) {
			return !$ignoreBoundary;
		}
		$intersect = ( ( $yi > $pt[ 1 ] ) !== ( $yj > $pt[ 1 ] ) )
&&			( $pt[ 0 ] < ( $xj - $xi ) * ( $pt[ 1 ] - $yi ) / ( $yj - $yi ) + $xi );
		if ( $intersect ) {
			$isInside = !$isInside;
		}
	}
	return $isInside;
}


}
