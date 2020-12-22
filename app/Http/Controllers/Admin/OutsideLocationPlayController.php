<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OutsideLocationPlay;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use JWTAuth;
use GuzzleHttp\Client;
use Carbon\Carbon;
class OutsideLocationPlayController extends Controller
{

     
    public function __construct()
    {
        $this->middleware('auth',['except' => ['OLPapi']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('admin.outside_location_play.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OutsideLocationPlay  $outsideLocationPlay
     * @return \Illuminate\Http\Response
     */
    public function show(OutsideLocationPlay $outsideLocationPlay)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OutsideLocationPlay  $outsideLocationPlay
     * @return \Illuminate\Http\Response
     */
    public function edit(OutsideLocationPlay $outsideLocationPlay)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OutsideLocationPlay  $outsideLocationPlay
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OutsideLocationPlay $outsideLocationPlay)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OutsideLocationPlay  $outsideLocationPlay
     * @return \Illuminate\Http\Response
     */
    public function destroy(OutsideLocationPlay $outsideLocationPlay)
    {
        //
    }

    public function OLPajaxData()
    {  
        return Datatables::of(OutsideLocationPlay::with('user')->get())
        ->addColumn('user', function ($row) {
           
           return $row->user->name;
            })  
            ->addColumn('coordinates', function ($row) {
           
           return $row->latitude.','.$row->longitude;
            })
        ->addColumn('action', function($row) {
            return ' <a href="/admin/Outsidelocationplay/edit/'. $row->id .'" class="btn btn-xs btn-primary">View/Edit</a>';
        })
        ->addIndexColumn()->make(true);

    }

    public function OLPapi(Request $request)
    { 
         if(empty(request('latitude')) || empty(request('longitude'))){
        return response()->json(['success'=>false,'response_code'=>400,'message' => 'both latitude and longitude required'], 400);
    }  

    $LAT= $request['latitude'];
    $LON = $request['longitude'];

    if(!is_coordinate($LAT, $LON)){
        $response = array('success' => false,'response_code'=>400,'message'=>'coordinates must only contain numbers or invalid coordinates');
        return response()->json($response, 400);}
      if(empty(request('deviceid'))){
        return response()->json(['success'=>false,'response_code'=>400,'message' => 'deviceid required'], 400);
    }
         $user=JWTAuth::user();
        $uid=  $user['id'];
        $deviceDetails	=getDeviceIdforAPI($request);
        
                try{
                    $client = new \GuzzleHttp\Client();
                    $response = $client->get('http://nominatim.openstreetmap.org/reverse?email=contact@streamlytv.com&format=json&lat='.$LAT.'&lon='.$LON.'&zoom=27&addressdetails=1');
       
                    $jsonfile=$response->getBody();
                    if (!json_decode($jsonfile, TRUE)) {return false;}
                    else{
                     $resp = json_decode($jsonfile, true);

         if( isset($resp['address']['postcode'] ) ){
            $zipcode= $resp['address']['postcode'];
            $address= $resp['display_name'];
        }
                 }

                }catch(\GuzzleHttp\Exception\RequestException $e){

                }
        $now=Carbon::now();

        $outsideLocation = OutsideLocationPlay::create([
            'users_id' => $uid,
            'zipcode'=>$zipcode??'00000',
            'longitude'=>$LAT,
            'latitude'=>$LON,
            'address'=>$address??'Unknown',
            'device_info'=>$deviceDetails,
            'timestamp'=>$now
        ]);
        
        $response = array('success' => true,'response_code'=>200,'message'=>'User outside location logged successfully.','data'=>$outsideLocation);
        return response()->json($response, 200);

    }
    
}
