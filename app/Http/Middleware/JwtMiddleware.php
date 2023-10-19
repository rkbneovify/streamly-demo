<?php

    namespace App\Http\Middleware;

    use Closure;
    use JWTAuth;
    use Exception;
    use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
    use App\SessionTable;
    use App\User;
    use Illuminate\Support\Facades\DB;
    use Carbon\Carbon;

    class JwtMiddleware extends BaseMiddleware
    {

        /**
         * Handle an incoming request.
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  \Closure  $next
         * @return mixed
         */
        public function handle($request, Closure $next)
        {
            try {
                $user = JWTAuth::parseToken()->authenticate();
            //     if($request->get('key')){
            //     $user = JWTAuth::parseToken()->authenticate();
            // }else{
            //     $acctokenNEw = SessionTable::where([
            //         ['access_token', '=', $request->bearerToken()],
            //     ])->select('users_id')->get();

            //     $useridcheck = $acctokenNEw[0]['users_id'];

            //     $useraccountcheck = User::where([
            //         ['id', '=', $useridcheck],
            //     ])->select('is_active')->get();

            //     $valueCheck = $useraccountcheck[0]['is_active'];
            //     if($valueCheck == 0){
            //         $res = array('status' => false,'success' => false,'message'=>'User account is deactivated','response_code'=>402);
            //         return response()->json($res, 402);
            //     }else{
            //         $user = JWTAuth::parseToken()->authenticate();
            //     }
            // }
            } catch (Exception $e) {
                if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                    $response = array('status' => false,'success' => false,'message'=>'Token is Invalid','response_code'=>401);
                    return response()->json($response, 401);
                }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                    $token = $request->bearerToken();
                    $acctoken = SessionTable::where([
                        ['status', '=', '1'],
                        ['access_token', '=', $token],
                         ])->get()->toArray();
                        // echo count($acctoken);
                        if ((count($acctoken)==0)) {  $response = array('status' => false,'success' => false,'message'=>'Token is Expired','response_code'=>401);
                            return response()->json($response, 401);}else{$rowid=  $acctoken[0]['id']; 
                        
                        $myarr=[];
                        array_push($myarr,$rowid ); $decremen=count($myarr);// number of id we will use this number to decrement the last value
                        $updatedExpireConcrenteUser=SessionTable::whereIn('id', $myarr)->update(['status' => 0,'modified_on' => Carbon::now()]);
                        SessionTable::where([
                            ['status', '=', '1'],
                            ['connected_devices', '!=', '1'],
                             ])
                    ->update([
                        'connected_devices' => DB::raw('`connected_devices` - 1 ')
                    ]);
                      
                    }
                    $response = array('status' => false,'success' => false,'message'=>'Token is Expired','response_code'=>401);
                    return response()->json($response, 401);
                }else{
                    $response = array('status' => false,'success' => false,'message'=>'Authorization Token not found','response_code'=>401);
                    return response()->json($response, 401);
                }
            }
            return $next($request);
        }
    }