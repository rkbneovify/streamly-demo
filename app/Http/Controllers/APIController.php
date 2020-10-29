<?php

namespace App\Http\Controllers;
use App\SessionTable;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use JWTAuth;
use Jenssegers\Agent\Agent;
use App\Models\JwtToken;
use Illuminate\Http\Request;
use App\User;
use App\Models\Role;
use Crypt;
use Hash;
use Mail;
use Carbon\Carbon;
use Validator;
use App\Models\Channel;
use Illuminate\Support\Facades\DB;
use App\Models\Genre;
use App\Models\RecentChannel;
use App\Models\FrontendText;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
class APIController extends Controller
{
    /**
     * @var bool
     */
    public $loginAfterSignUp = true;
    public function __construct()
    {
        $this->middleware('jwt.verify', ['except' => ['login','register','verify','support','privacypol','termofuse','qrcodeapi']]);
    }

    public function index()
    {
        $users = User::all();
        $data = $users->toArray();

        $response = [
            'success' => true,
            'response_code'=>200,
            'data' => $data,
            'message' => 'User retrieved successfully.'
        ];

        return response()->json($response, 200);
    }
    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        $data = $user->toArray();

        if (is_null($user)) {
            $response = [
                'success' => false,
                'data' => 'Empty',
                'message' => 'User not found.',
                'response_code'=>404,
            ];
            return response()->json($response, 404);
        }


        $response = [
            'success' => true,
            'data' => $data,
            'message' => 'User retrieved successfully.','response_code'=>200,
        ];

        return response()->json($response, 200);
    }

    
     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'email' => 'required',
            'first_name' => 'required'
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'data' => 'Validation Error.',
                'message' => $validator->errors(),
                'response_code'=>404,
            ];
            return response()->json($response, 404);
        }
        $updated = $user->fill($request->all())->save();
        

        $data = $user->toArray();

        $response = [
            'success' => true,
            'data' => $data,
            'message' => 'User updated successfully.',
            'response_code'=>200,
        ];

        return response()->json($response, 200);
    }






    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
public function login(Request $request)
    {
        $credentials = request(['email', 'password']);
        

        if(empty(request('email')) || empty(request('password'))){
            return response()->json(['success'=>false,'response_code'=>401,'message' => 'both email and password required'], 401);
        }
    
        if (! $token = JWTAuth::attempt($credentials)) {
            return response()->json(['success'=>false,'response_code'=>401,'message' => 'Invalid credentials'], 401);
        }
        $user = JWTAuth::user();//all the user info
        // if($user->email_verified_at==null){
        //     return response()->json(['success'=>false,'message' => 'Email not verfied, verify your email first.'], 401);   
        // }

        if($user->is_active==0){
            return response()->json(['success'=>false,'response_code'=>401,'message' => 'You are deactivated, kindly contact admin.'], 401);   
        }

      $uid=  $user['id'];
      $this->authenticated($request,$user,$token);
        
   checkLoginLimit($uid,$token);


   
}

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function logout(Request $request)
    {
        // $this->validate($request, [
        //    // 'token' => 'required'
        // ]);
        $token = $request->only( 'token');
      
        $acctoken = SessionTable::where([
            ['status', '=', '1'],
            ['access_token', '=', $token],
             ])->get()->toArray();
 //print_r($acctoken); exit;

        try {  //$user = JWTAuth::user();//all the user info
            //$uid=  $user['id'];
            //JWTAuth::invalidate($request->token);
            if($token==''){
             //   echo 'user cant be logout';
                return response()->json([
                    'success' => false,
                    'message' => 'the user cannot be logged out because reqired token is not provided',
                    'response_code'=>401,
                   
                ]);
            } else   if ((count($acctoken)==0)){ 
                $response = [
                    'success' => false,
                    'data' => 'Empty',
                    'message' => 'access token not found.','response_code'=>404,
                ];
                return response()->json($response, 404);

            } else {

              $id=  $acctoken[0]['id'];
                LogoutandDecrementDevice($id);
            }
            
        } catch (JWTException $exception) {
            //echo 'user cant be logout2';
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out',
                'error'=>$exception,
                'response_code'=>500,
                
            ], 500);
        }
    }



    protected function authenticated($request, $user, $token)
    {
        $olduser = $user;
        $user->last_login = date("Y-m-d H:i:s");
        $user->save();

        $agent = new Agent();

        $isDesktop = $agent->isDesktop();
        $isPhone = $agent->isPhone();
        $jwtToken = new JwtToken();
        $jwtToken->user_id = $user->id;
        $jwtToken->token = $token;
        $jwtToken->browser = $agent->browser();;
        $jwtToken->platform = $agent->platform();
        $jwtToken->device = $agent->device();
        $mobileHeader = $request->header('x_platform');
        if (isset($mobileHeader) && $mobileHeader == 'mobile') {
            JwtToken::where('user_id',$user->id)->where('phone',1)->delete();
            
            $jwtToken->phone = 1;
            $jwtToken->save();

        } else {
            JwtToken::where('user_id',$user->id)->where('desktop',1)->delete();
            
            $jwtToken->desktop = 1;
            $jwtToken->save();
        } 
    }

    public function connecteddevice(){
        $user = JWTAuth::user();//all the user info
        $uid=  $user['id'];
        //and get the number of last connected device
        $prevConnectDevicecount = SessionTable::selectRaw(" connected_devices, SUBSTRING_INDEX(mac_address, '#~', -1) device_id,SUBSTRING_INDEX(mac_address, '#~', 1) device_name")
        ->where([
        ['status', '=', '1'],
        ['users_id', '=', $uid],
         ])->orderBy('connected_devices', 'asc');
        $s='';$numofdevice='';
    $s=$prevConnectDevicecount->get();
    
    $numofdevice=$prevConnectDevicecount->count();
   
     
         $response = array('success' =>true ,'response_code'=>200,'numofdevice'=>$numofdevice,'message'=>'Connected device found successfully','data'=>$s);
         return response()->json($response, 200);
         

    }

    public function support()
    {  $frontendjson = FrontendText::find(1)->frontendjson;
        $supportemail=$frontendjson['supportemail']??'contact@streamlytv.com';
       $supportphone= $frontendjson['supportphone']??'1234656477';
       $supportphone2= $frontendjson['supportphone2']??'773727828';
       $message= $frontendjson['supportapitext']??'For help and support contact on email below';
        $supportnumber=[];
        $supportnumber=['email'=>$supportemail,'num1'=>$supportphone,'num2'=>$supportphone2];
        $response = array('success' =>true ,'response_code'=>200,'data'=>$supportnumber,'message'=>$message);
        return response()->json($response, 200);
    }
    public function privacypol()
    { $breakingpoint= '#~';
       // echo json_encode( explode("\r\n",file_get_contents('data.txt')) );
        $file = file_get_contents('PrivacyPolicy.txt');
        $response = array('success' =>true ,'response_code'=>200,'data'=>$file,'message'=>'Privacy policy');
        return response()->json($response, 200);
    }
    
    public function termofuse()
    { $breakingpoint= '#~';
       // echo json_encode( explode("\r\n",file_get_contents('data.txt')) );
        $file = file_get_contents('Termofuse.txt');
        $response = array('success' =>true ,'response_code'=>200,'data'=>$file,'message'=>'Term of use');
        return response()->json($response, 200);
    }

    public function genrename(Request $request )
    { $ua = $request->server('HTTP_USER_AGENT');
        $isDevice=isDeviceAppleRokuOthers($ua);
        if($isDevice=='apple')   {
        $record = Channel::select('channels.id','channels.image', 'channels.url',DB::raw('IFNULL(channels.callSign,channels.name) callSign'))
        
        
        ->selectRaw("CAST( CONCAT('[\"', REPLACE(GROUP_CONCAT(IFNULL(g.name, 'Others')), ',', '\",\"'), '\"]') AS JSON ) genrename")
        
        
        ->leftJoin('genre as g', DB::raw("FIND_IN_SET(g.id, channels.genres)"), DB::raw('>'),DB::raw('0'))
    //     ->whereIn('channels.callSign', function($query)
    // {
    //     $query->select('CallSign')
    //           ->from('gracenotechannels');
              
    // })
    ->groupBy('channels.id') ->orderBy('channels.callSign', 'asc');
    
       
      $data=$record->get()->groupBy('genrename');} else{
        $record = Channel::select('channels.id', 'channels.callSign','channels.image', 'channels.dash_url as url',DB::raw('IFNULL(channels.callSign,channels.name) callSign'))
        
        
        ->selectRaw("CAST( CONCAT('[\"', REPLACE(GROUP_CONCAT(IFNULL(g.name, 'Others')), ',', '\",\"'), '\"]') AS JSON ) genrename")
        
        
        ->leftJoin('genre as g', DB::raw("FIND_IN_SET(g.id, channels.genres)"), DB::raw('>'),DB::raw('0'))
    //     ->whereIn('channels.callSign', function($query)
    // {
    //     $query->select('CallSign')
    //           ->from('gracenotechannels');
              
    // })
    ->groupBy('channels.id') ->orderBy('channels.callSign', 'asc');
    
       
      $data=$record->get()->groupBy('genrename'); 
      }
      $a=$data->keys();
      
      $response = [
        'success' => true,
        
        'allGenre'=>$a,
        'data' => $data,
        'message' => 'Channel with genre feteched successfully.',
        'response_code'=>200,
    ];
    return response()->json($response, 200);
    }

    public function favoriteChannel(Request $request)
    { try { 
        $input = $request->all();
        $channelid = $request->input('channelid');
        JWTAuth::user()->favorites()->syncWithoutDetaching($channelid);
        $response = [
            'success' => true,
            'data' => 'Channel id '.$channelid.' added' ,
            'message' => 'Successfully added to favourite channel','response_code'=>200,
        ];
        return response()->json($response, 200);
    } catch (Exception $exception) {
        
        return response()->json([
            'success' => false,
            'message' => 'Some Error occured',
            'error'=>$exception->getMessage(),
            'response_code'=>500,
            
        ], 500);
    }
        
    }
    
    /**
     * Unfavorite a particular post
     *
     * @param  Post $post
     * @return Response
     */
    public function unFavoriteChannel(Request $request)
    {  try { 
        $input = $request->all();
        $channelid = $request->input('channelid');
        JWTAuth::user()->favorites()->detach($channelid);
        $response = [
            'success' => true,
            'data' => 'Channel id '.$channelid.' removed' ,
            'message' => 'Successfully removed from favourite channel list','response_code'=>200,
        ];
        return response()->json($response, 200);
    } catch (Exception $exception) {
        
        return response()->json([
            'success' => false,
            'message' => 'Some Error occured',
            'error'=>$exception->getMessage(),
            'response_code'=>500,
            
        ], 500);
    }
    
        
    }
    
    public function myFavorites(Request $request)
    { try {   
        $ua = $request->server('HTTP_USER_AGENT');
        $isDevice=isDeviceAppleRokuOthers($ua);
        $myFavorites = JWTAuth::user()->favorites;

    
        if($isDevice=='apple')  {
            $myFavorites->map(function ($address)  {

              unset($address['dash_url']);
                return $address;
            
            });
        }else{
        
        $myFavorites->map(function ($address)  {

                 
            $address['url'] = $address['dash_url'];
          unset($address['dash_url']);
            return $address;
        
        });
    }
        
        $response = [
            'success' => true,
            'data' => $myFavorites->unique('id'),
            'message' => 'My favourites.','response_code'=>200,
        ];
        return response()->json($response, 200);
    } catch (Exception $exception) {
        
        return response()->json([
            'success' => false,
            'message' => 'Some Error occured',
            'error'=>$exception->getMessage(),
            'response_code'=>500,
            
        ], 500);
    }
    
    }

    public function addToRecent(Request $request){
        $limit = 10;
        try { $id =JWTAuth::user()->id;
            $count = RecentChannel::where('user_id','=',$id)->count();
if($count >=  $limit){
    RecentChannel::where('user_id','=',$id)->orderBy('created_at','desc')->skip(5)->delete();
}
        $now = Carbon::now('utc')->toDateTimeString();
       
        
        $channelid = $request->input('channelids');
       
        $channels= preg_split('/,/', $channelid, -1, PREG_SPLIT_NO_EMPTY);
        $channelArr= array_unique($channels, SORT_NUMERIC);
        $model=new RecentChannel;
    
        

foreach ($channelArr as $value) {
    $model->updateOrCreate(
        [
            'channels_id' => $value,
            'user_id'  => $id,
            'updated_at'=> $now
        ],
        [
            'channels_id' => $value,
            'user_id' => $id,
            'created_at'=> $now,
            'updated_at'=> $now
        ]
    );
}
$ua = $request->server('HTTP_USER_AGENT');
$isDevice=isDeviceAppleRokuOthers($ua);
        
$recentschannel = JWTAuth::user()->recentschannel;
if($isDevice=='apple')    {
    $recentschannel->map(function ($address)  {

      unset($address['dash_url']);
        return $address;
    
    });
}else{

$recentschannel->map(function ($address)  {

         
    $address['url'] = $address['dash_url'];
  unset($address['dash_url']);
    return $address;

});
}

     $response = [
        'success' => true,
        'data' => $recentschannel,
        'message' => 'Added to recent','response_code'=>200,
    ];
     
     return response()->json($response,200);}
     catch (Exception $exception) {
        
        return response()->json([
            'success' => false,
            'message' => 'Some Error occured',
            'error'=>$exception->getMessage(),
            'response_code'=>500,
            
        ], 500);
    }

    
}
public function myRecents(Request $request)
    { try { 
        
        $ua = $request->server('HTTP_USER_AGENT');
        $isDevice=isDeviceAppleRokuOthers($ua);
        
       
        $recentschannel = JWTAuth::user()->recentschannel;
        if($isDevice=='apple')   {
            $recentschannel->map(function ($address)  {

              unset($address['dash_url']);
                return $address;
            
            });
        }else{
        
        $recentschannel->map(function ($address)  {

                 
            $address['url'] = $address['dash_url'];
          unset($address['dash_url']);
            return $address;
        
        });
    }
       

        $response = [
            'success' => true,
            'data' => $recentschannel,
            'message' => 'My Recent.','response_code'=>200,
        ];
        return response()->json($response, 200);
    } catch (Exception $exception) {
        
        return response()->json([
            'success' => false,
            'message' => 'Some Error occured',
            'error'=>$exception->getMessage(),
            'response_code'=>500,
            
        ], 500);
    }
    
    }

    public function qrcodeapi(){
     //use this to generate svg file and then manually convert that svg to png
        // $qrcode=QrCode::size(250)->generate('http://streamlytv.com/'); 
        
        $imagePath=asset("/frontend/img/streamlyQRcode.png");

        $response = [
            'success' => true,
            'data' => $imagePath,
            'message' => 'http://streamlytv.com/','response_code'=>200,
        ];
        return response()->json($response, 200);
       }
}