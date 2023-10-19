<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Models\Subscription;
use Mail;
use Hash;
use Crypt;
use JWTAuth;
use App\Http\Controllers\Auth\AuthController;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class PaymentlinkController extends Controller

    {
        public function getTinyUrl($longUrl) 
        {
        $apiUrl = "http://tinyurl.com/api-create.php?url=" . urlencode($longUrl);
        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $response = curl_exec($ch);
        if (curl_errno($ch)) 
        {
        $errorMessage = curl_error($ch);
        echo "cURL Error: " . $errorMessage;
        return false;
        }
        curl_close($ch);
        return $response;
        }

        public function index(Request $request) {
            return view('paymentlink');
        }

        public function addmasteruser(Request $request) {
            return view('addmasteruser');
        }

        public function paymentsuccess(Request $request) {
            return view('success');
        }

        public function paymentfail(Request $request) {
            return view('fail');
        }

        public function masteruserlist() {

            $users = DB::table('master_user')
            ->join('users', 'master_user.user_id', '=', 'users.id')
            ->select('master_user.*', 'users.email_verified_at')
            ->get();
            
            return view('masterusers', compact('users'));

        }

        public function masteruserpagination(Request $request) {
            // Get the search input from DataTables
            $search = $request->input('search.value');
            
            // Define the base query
            $query = DB::table('master_user')
                ->join('users', 'master_user.user_id', '=', 'users.id')
                ->select('master_user.*', 'users.email_verified_at');
        
            // Apply search filter if the search input is not empty
            if (!empty($search)) {
                $query->where('master_user.user_email', 'LIKE', '%' . $search . '%');
            }
        
            // Get the total records (without pagination)
            $totalRecords = $query->count();
        
            // Apply ordering and pagination using skip and take
            $query->orderBy('master_user.id', 'asc') // Change 'asc' to 'desc' if you want a different sorting order
                ->skip($request->input('start'))
                ->take($request->input('length'));
        
            // Execute the query
            $users = $query->get();
        
            // Prepare the response for DataTables
            $response = [
                'draw' => $request->input('draw'),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords,
                'data' => $users,
            ];
        
            return response()->json($response);
        }

        public function paymentlist(){

            $paylinks = DB::table('payment_links')
            ->join('users', 'payment_links.user_id', '=', 'users.id')
            ->get();

            return view('paymentlist', compact('paylinks'));
        }

        public function paymentlistpagination(Request $request) {
            // Get the search input from DataTables
            $search = $request->input('search.value');
        
            // Define the base query
            $query = DB::table('payment_links')
                ->select('payment_links.*');
        
            // Apply search filter if the search input is not empty
            if (!empty($search)) {
                $query->where('payment_links.email', 'LIKE', '%' . $search . '%')
                ->orWhere('payment_links.name', 'LIKE', '%' . $search . '%');
            }
        
            // Get the total records (without pagination)
            $totalRecords = $query->count();
        
            // Apply ordering by 'id' in ascending order
            $query->orderBy('payment_links.id', 'asc') // Change to 'desc' if you want descending order
                ->skip($request->input('start'))
                ->take($request->input('length'));
        
            // Execute the query
            $paylinks = $query->get();
        
            // Prepare the response for DataTables
            $response = [
                'draw' => $request->input('draw'),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords,
                'data' => $paylinks,
            ];
        
            return response()->json($response);
        }

        public function paymentlinks(Request $request) {

        $name=$request['name'];
        $email=$request['email'];
        $phone=$request['phone'];
        $payment_type=$request['payment_type'];
        $device_limit=$request['device_limit'];
        $description=$request['description'];
        $amount=$request['amount'] * 100;
        if($request['payment_type'] == 'charge'){
        $periodTime = null;
        }else{
        $periodTime = $request['period'];
        };

        $checkEmail = DB::table('master_user')
        ->join('users', 'master_user.user_id', '=', 'users.id')
        ->where('user_email', $email)->first();

        //dd($checkEmail);
        if($checkEmail){
            if(!$checkEmail->email_verified_at == Null){
            $tinyUrl = $this->getTinyUrl('https://streamlytv.com/checkout/'.$checkEmail->user_id.'');
            
            $payment_links = DB::table('payment_links')->insert(
                array(
                   'user_id' => $checkEmail->user_id,
                   'name' => $name,
                   'email' => $email,
                   'phone' => $phone,
                   'payment_type' => $payment_type,
                   'description' => $description,
                   'amount' => $amount,
                   'period' => $periodTime
                )
             );
          
             if($payment_links){
                $html='<html>
                        Hi, '.$name.'<br><br>
                        To activate your subscription, please make the payment by clicking on the link below!<br>
                        <a href="'.$tinyUrl.'" id="checkout-button" class="btn">Click Here</a>
                        <br><br>
                        Thanks<br>
                        Team Streamly
                    </html>';
    
                       Mail::send('emails.general',["html"=>$html] , function($message) use ($request, $email, $name){
                       $message->to($email, $name);
                       $message->bcc('piyush.mishra@oodles.io')
                       ->subject('Payment Link. '.env('APP_NAME'));
                   });
    
                return redirect('salesuser/paymentlink')->with('info','Payment link send to user successfully!');; 
            }else{
                return redirect('salesuser/paymentlink')->with('error','Payment link not generated!');
            }
        }else{
            return redirect('salesuser/paymentlink')->with('error','This master user has not activated yet. Please ask him to check email and verify the account.');
        }
        }else{
            return redirect('salesuser/paymentlink')->with('error','Email not exists in master user');
        }

        }

        public function createmasteruser(Request $request) {
            //return $request;
            $result = (new AuthController)->register($request);
  
            $salesuserEmail = Auth::user()->email;
            $responseData = $result->getData();

            if($responseData->response_code == 200){
                $master_user = DB::table('master_user')->insert(
                    array(
                       'user_id' => $responseData->data->id,
                       'user_email' => $responseData->data->email,
                       'register_by' => $salesuserEmail
                    )
                 );

            //     if($master_user){
            //         $html='<html>
            //         Hi, '.$responseData->data->name.'<br><br>
            //         Here is your login credentials<br>
            //         Email: '.$responseData->data->email.'<br>
            //         Password: '.$request->password.'<br>
            //         <br><br>
            //         Thanks<br>
            //         Team Streamly
            //     </html>';

            //        Mail::send('emails.general',["html"=>$html] , function($message) use ($request, $responseData){
            //        $message->to($responseData->data->email, $responseData->data->name);
            //        $message->bcc('piyush.mishra@oodles.io')
            //        ->subject('You streamly account login credentails. '.env('APP_NAME'));
            //    });
            //     }

                return redirect('salesuser/add_masteruser')->with('info', $responseData->message);
                
            }elseif(isset($responseData->data->zipcode) && !empty($responseData->data->zipcode[0])){
                return redirect('salesuser/add_masteruser')->with('error', $responseData->data->zipcode[0]);
            }elseif(isset($responseData->data->email) && !empty($responseData->data->email[0])){
                if($responseData->data->email[0] == 'OOPS! The email you used to register is already associated with a Streamly account. Please try to login with that email, or do a password reset if you dont have the password.'){

                    $users = DB::table('users')
                    ->where('email', '=', $request->email)
                    ->get('email_verified_at');

                    if($users[0]->email_verified_at == null){
                        return redirect('salesuser/add_masteruser')->with('error_info', "OOPS! This user is registered with Streamly but hasn't activated their account. Kindly ask them to verify before proceeding to create a payment.")->with('user_email', $request->email);;
                    }else{
                        return redirect('salesuser/add_masteruser')->with('error', 'OOPS! This user is already registered with streamly! You can now create a payment link for them.');
                    }
                }else{
                return redirect('salesuser/add_masteruser')->with('error', $responseData->data->email[0]);
                }
            }else{
                return redirect('salesuser/add_masteruser')->with('error', $responseData);
            }
           
        }

        public function resendVerificationEmail($email){

            $User = DB::table('users')
            ->where('email', '=', $email)
            ->get();

            $newPassword = "123456789";
            $hashedPassword = Hash::make($newPassword);
            
            $resetpass = User::where('email', '=', $email)->update([
                'password' => $hashedPassword,
            ]);

            if($resetpass){
            
            $verification_code=Crypt::encrypt($email);

            $FRONTEND_URL=env('FRONTEND_URL');

            $account_verification_link=$FRONTEND_URL.'/api/auth/verify?token='.$verification_code;

            $html2='<html>
            Hi, '.$User[0]->name.'<br><br>

            Thank you for registering for '.env('APP_NAME').'.<br>
            Please click the following link to verify your account.
            <br><br><a href="'.$account_verification_link.'" target=_blank style="  display: block;
            width: 115px;
            height: 25px;
            background: #004E92 0% 0% no-repeat padding-box;
            padding: 10px;
            text-align: center;
            border-radius: 5px;
            color: white;
            font-weight: bold;
            line-height: 25px;">Verify</a><br>
            Your login credentials<br>
            Email: '.$User[0]->email.'<br>
            Password: 123456789<br><br>
            Thank you!<br>
            The '.env('APP_NAME').' Team!
            </html>';

            Mail::send('emails.general',["html"=>$html2] , function($message) use ($User){
                $message->to($User[0]->email, $User[0]->name);
                $message->bcc('piyush.mishra@oodles.io')
                ->subject(env('APP_NAME').': Account Verification and Signup Details');
            });
            
            return redirect('salesuser/add_masteruser')->with('info', 'Verification email send to '.$User[0]->email.' successfully.');
            }else{
                return redirect('salesuser/add_masteruser')->with('error', 'OOPS! Email not send to user something went wrong!');
            }
        }

        public function checkout($master_id){

            $masteruserData = User::where('id', $master_id)->first();
            $plandetails_masteruser = DB::table('payment_links')->where('user_id', $master_id)->latest('created_at')->first();
         
            if($plandetails_masteruser->status){
                return view('alreadydone', compact('masteruserData'));
            }else{
                return view('checkout', compact('masteruserData', 'plandetails_masteruser'));
            }
        }

        public function backendpayment(Request $request){
        try{
        $STRIPE_KEY=DB::table('Stripe_keys')->where('status', 'active')->get();
        if($STRIPE_KEY[0]->type == "Live"){
            $custom_productID = 'prod_OXSTSTeCyMUx3T';
        }else{
            $custom_productID = 'prod_OXWupQNgUjSA4l';
        };
        if($request['paytype'] == "charge"){
            \Stripe\Stripe::setApiKey($STRIPE_KEY[0]->stripe_secret);

    		$token = \Stripe\Token::create([
										        'card' => [
										            'number' => $request['cardnumber'], // Replace with actual card number
										            'exp_month' => $request['expmonth'],
										            'exp_year' => $request['expyear'],
										            'cvc' => $request['cvv'], // Replace with actual CVC
										        ],
										    ]);
			 $cardToken = $token->id;
			 //dd($cardToken);

			 $customer = \Stripe\Customer::create([
					        'email' =>  $request['email'], // Replace with customer's email
					        'source' => $cardToken,
					        'name' =>  $request['name'],
					        'phone' =>  $request['phone'],
					        'address' => [
					            'line1' => $request['address'],
					            'line2' => $request['address'],
					            'city' => $request['city'],
					            'state' => $request['state'],
					            'postal_code' => '86336',
					            'country' => $request['country'],
					        ],

					    ]);
             $customerId = $customer->id;
             //exit;

             $charge = \Stripe\Charge::create([
					        'amount' =>  $request['txthprice'], // Amount in cents ($20.00)
					        'currency' => 'usd',
					        'description' => 'Test payment-One Time',
					        'customer' => $customerId,
					    ]);
             $chargeId = $charge->id;
             //exit;
             //  dd($charge);
            if(!empty($chargeId))
             {
                User::where('email', $request['email'])
                ->update([
                'stripe_id' => $customerId 
                ]);

                DB::table('subscriptions')->insert(
                    array(
                    'user_id' => $request->pid,
                    'name' => 'main',
                    'stripe_id' => $chargeId,
                    'stripe_status' => 'active',
                    'stripe_plan' => 'custom',
                    'quantity' => $request['quantity'],
                    'created_at' => date("Y/m/d h:i:s"),
                    )
                 );

                DB::table('payment_links')
                ->where('id', $request['hidden_id']) // Assuming user_id is the identifier
                ->update([
                'status' => 1,
                ]);

                $html='<html>
                Hi, '.$request['fullname'].'<br><br>
                We delighted to inform you that your payment has been successfully processed. You can view your transaction recipt by <a href="'.$charge->receipt_url.'" id="checkout-button" class="btn">[ Click Here ]</a>.
                <br><br>
                Thanks<br>
                Team Streamly
            </html>';

               Mail::send('emails.general',["html"=>$html] , function($message) use ($request){
               $message->to($request['email'], $request['name']);
               $message->bcc('piyush.mishra@oodles.io')
               ->subject('Payment Successful - Thank You!. '.env('APP_NAME'));
            });

                return 'payment success';
                // return 'Payment Successful';
             }else{
                return 'payment fail';
                // return 'Payment Not Successful';
             }
        }elseif($request['paytype'] == "subscription"){
            \Stripe\Stripe::setApiKey($STRIPE_KEY[0]->stripe_secret);

    		$token = \Stripe\Token::create([
										        'card' => [
										            'number' => $request['cardnumber'], // Replace with actual card number
										            'exp_month' => $request['expmonth'],
										            'exp_year' => $request['expyear'],
										            'cvc' => $request['cvv'], // Replace with actual CVC
										        ],
										    ]);
			 $cardToken = $token->id;
			 //dd($cardToken);

			 $customer = \Stripe\Customer::create([
					        'email' =>  $request['email'], // Replace with customer's email
					        'source' => $cardToken,
					        'name' =>  $request['name'],
					        'phone' =>  $request['phone'],
					        'address' => [
					            'line1' => $request['address'],
					            'line2' => $request['address'],
					            'city' => $request['city'],
					            'state' => $request['state'],
					            'postal_code' => '86336',
					            'country' => $request['country'],
					        ],

					    ]);
             $customerId = $customer->id;
             //exit;

             $product = \Stripe\Plan::create([
                'amount' => $request['txthprice'],
                'currency' => 'usd',
                'interval' => $request['period'],
                'product' => 'prod_OXSTSTeCyMUx3T',
              ]);

             $priceid = $product->id;

             $charge = \Stripe\Subscription::create([
                'customer' => $customerId,
                'items' => [
                  ['price' => $priceid],
                ],
                'expand' => ['latest_invoice.payment_intent'],
              ]);

             $chargeId = $charge->id;
             //exit;

            if(!empty($chargeId))
             {
                User::where('email', $request['email'])
                ->update([
                'stripe_id' => $customerId 
                ]);

                DB::table('subscriptions')->insert(
                    array(
                    'user_id' => $request->pid,
                    'name' => 'main',
                    'stripe_id' => $chargeId,
                    'stripe_status' => 'active',
                    'stripe_plan' => $priceid,
                    'quantity' => $request['quantity'],
                    'created_at' => date("Y/m/d h:i:s"),
                    )
                 );

                DB::table('payment_links')
                ->where('id', $request['hidden_id']) // Assuming user_id is the identifier
                ->update([
                'status' => 1,
                ]);

                $html='<html>
                Hi, '.$request['fullname'].'<br><br>
                We delighted to inform you that your payment has been successfully processed. You can view your transaction recipt by <a href="'.$charge->latest_invoice->hosted_invoice_url.'" id="checkout-button" class="btn">[ Click Here ]</a>.
                <br><br>
                Thanks<br>
                Team Streamly
            </html>';

               Mail::send('emails.general',["html"=>$html] , function($message) use ($request){
               $message->to($request['email'], $request['name']);
               $message->bcc('piyush.mishra@oodles.io')
               ->subject('Payment Successful - Thank You!. '.env('APP_NAME'));
            });

                return 'payment success';
                // return 'Payment Successful';
             }else{
                return 'payment fail';
                // return 'Payment Not Successful';
             }
        }else{
            return 'Invalid Request';
        };
        }catch(\Stripe\Exception\CardException $ex) 
         {	
           echo "Something Went Wrong...!,Error Occured:".$ex->getMessage(); 
        }catch (\Stripe\Exception\RateLimitException $ex) 
        {
           echo "Something Went Wrong...!,Error Occured:".$ex->getMessage(); 
        } 
        catch (\Stripe\Exception\InvalidRequestException $ex) 
        {
           echo "Something Went Wrong...!,Error Occured:".$ex->getMessage(); 
        } 
        catch (\Stripe\Exception\AuthenticationException $ex) 
        {
           echo "Something Went Wrong...!,Error Occured:".$ex->getMessage(); 
        } 
        catch (\Stripe\Exception\ApiConnectionException $ex) 
        {
            echo "Something Went Wrong...!,Error Occured:".$ex->getMessage(); 
        } 
        catch (\Stripe\Exception\ApiErrorException $ex) 
        {	
           echo "Something Went Wrong...!,Error Occured:".$ex->getMessage(); 
        }
        catch (phpmailerException $ex) 
        {
          echo $ex->errorMessage(); //error messages from PHPMailer
        } 
        catch(Exception $ex)
        {
            echo "Something Went Wrong...!,Error Occured:".$ex->getMessage(); 
            echo "Message/Mail could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
        }

    }