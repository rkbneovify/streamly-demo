<?php

namespace App\Http\Controllers;
use JWTAuth;
use App\User;
use App\SessionTable;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\RegistrationFormRequest;

class APIController extends Controller
{
    /**
     * @var bool
     */
    public $loginAfterSignUp = true;

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
public function login(Request $request)
    {
        $input = $request->only('email', 'password');
        $token = null;

        if (!$token = JWTAuth::attempt($input)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], 401);
        }


        



        // to detect browser
        
        // Function written and tested December, 2018
function get_browserAndPlatform_name($user_agent)
{
        // Make case insensitive.
        $t = strtolower($user_agent);

        // If the string *starts* with the string, strpos returns 0 (i.e., FALSE). Do a ghetto hack and start with a space.
        // "[strpos()] may return Boolean FALSE, but may also return a non-Boolean value which evaluates to FALSE."
        //        http://php.net/manual/en/function.strpos.php
        $t = " " . $t;

        // Humans / Regular Users      
        if     (strpos($t, 'opera'     ) || strpos($t, 'opr/')     ) return 'Opera'            ;
        elseif (strpos($t, 'edge'      )                           ) return 'Edge'             ;
        elseif (strpos($t, 'chrome'    )                           ) return 'Chrome'           ;
        elseif (strpos($t, 'safari'    )                           ) return 'Safari'           ;
        elseif (strpos($t, 'firefox'   )                           ) return 'Firefox'          ;
        elseif (strpos($t, 'msie'      ) || strpos($t, 'trident/7')) return 'Internet Explorer';

        // Search Engines  
        elseif (strpos($t, 'google'    )                           ) return '[Bot] Googlebot'   ;
        elseif (strpos($t, 'bing'      )                           ) return '[Bot] Bingbot'     ;
        elseif (strpos($t, 'slurp'     )                           ) return '[Bot] Yahoo! Slurp';
        elseif (strpos($t, 'duckduckgo')                           ) return '[Bot] DuckDuckBot' ;
        elseif (strpos($t, 'baidu'     )                           ) return '[Bot] Baidu'       ;
        elseif (strpos($t, 'yandex'    )                           ) return '[Bot] Yandex'      ;
        elseif (strpos($t, 'sogou'     )                           ) return '[Bot] Sogou'       ;
        elseif (strpos($t, 'exabot'    )                           ) return '[Bot] Exabot'      ;
        elseif (strpos($t, 'msn'       )                           ) return '[Bot] MSN'         ;

        // Common Tools and Bots
        elseif (strpos($t, 'mj12bot'   )                           ) return '[Bot] Majestic'     ;
        elseif (strpos($t, 'ahrefs'    )                           ) return '[Bot] Ahrefs'       ;
        elseif (strpos($t, 'semrush'   )                           ) return '[Bot] SEMRush'      ;
        elseif (strpos($t, 'rogerbot'  ) || strpos($t, 'dotbot')   ) return '[Bot] Moz or OpenSiteExplorer';
        elseif (strpos($t, 'frog'      ) || strpos($t, 'screaming')) return '[Bot] Screaming Frog';
        elseif (strpos($t, 'blex'      )                           ) return '[Bot] BLEXBot'       ;
        
        // Miscellaneous 
        elseif (strpos($t, 'facebook'  )                           ) return '[Bot] Facebook'     ;
        elseif (strpos($t, 'pinterest' )                           ) return '[Bot] Pinterest'    ;
        
        // Check for strings commonly used in bot user agents   
        elseif (strpos($t, 'crawler' ) || strpos($t, 'api'    ) ||
                strpos($t, 'spider'  ) || strpos($t, 'http'   ) ||
                strpos($t, 'bot'     ) || strpos($t, 'archive') || 
                strpos($t, 'info'    ) || strpos($t, 'data'   )    ) return '[Bot] Other'   ;
        //Get the operating_system
        $operating_system = 'Unknown Operating System';
    if (preg_match('/linux/i', $user_agent)) {
        $operating_system = 'Linux';
    } elseif (preg_match('/macintosh|mac os x|mac_powerpc/i', $user_agent)) {
        $operating_system = 'Mac';
    } elseif (preg_match('/windows|win32|win98|win95|win16/i', $user_agent)) {
        $operating_system = 'Windows';
    } elseif (preg_match('/ubuntu/i', $user_agent)) {
        $operating_system = 'Ubuntu';
    } elseif (preg_match('/iphone/i', $user_agent)) {
        $operating_system = 'IPhone';
    } elseif (preg_match('/ipod/i', $user_agent)) {
        $operating_system = 'IPod';
    } elseif (preg_match('/ipad/i', $user_agent)) {
        $operating_system = 'IPad';
    } elseif (preg_match('/android/i', $user_agent)) {
        $operating_system = 'Android';
    } elseif (preg_match('/blackberry/i', $user_agent)) {
        $operating_system = 'Blackberry';
    } elseif (preg_match('/webos/i', $user_agent)) {
        $operating_system = 'Mobile';
    }
        return 'Other (Unknown)'.$operating_system;
}

$u_agent = $_SERVER['HTTP_USER_AGENT'];
$browser=get_browserAndPlatform_name($u_agent);
    
return response()->json([
    'success' => true,
    'token' => $token,
    'browser_name'=>$browser
]);
}

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function logout(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out'
            ], 500);
        }
    }

    /**
     * @param RegistrationFormRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegistrationFormRequest $request)
    { $date = date('Y-m-d H:i:s');
        $user = new User();
        $user->first_name = $request->first_name;
        $user->role_id = 1;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->last_name = $request->last_name;
        $user->phone = $request->phone;
        $user->address = 'adress 1';
        $user->address2 = 'adress 2';
        $user->postal_code = '111111';
        $user->state = 'state';
        $user->country = 'country';
        $user->active = 'x';
        $user->created_on = $date;
        $user->modified_on = $date;
        $user->save();

        if ($this->loginAfterSignUp) {
            return $this->login($request);
        }

        return response()->json([
            'success'   =>  true,
            'data'      =>  $user
        ], 200);
    }
}
