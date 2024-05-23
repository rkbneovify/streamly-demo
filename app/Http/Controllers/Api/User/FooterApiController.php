<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class FooterApiController extends Controller
{
    public function contactForm(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|max:50',
            'message' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => 'validation_error',
                'message' => $validator->errors()
            ], 400);
        } else {
            $html = '<html>
                Hi, My name is ' . $request['name'] . '<br><br>
                Email: ' . $request['email'] . '<br><br>
                Message: ' . $request['message'] . '.<br><br>
            </html>';

            try {
                Mail::send('emails.general', ["html" => $html], function ($message) use ($request) {
                    $message->to(env('MAIL_FROM_ADDRESS'), env('APP_NAME'))
                        ->subject(env('APP_NAME') . ': Contact Form');
                });

                return response()->json([
                    'status' => 'success',
                    'message' => 'Thanks for contacting us!'
                ], 200);
            } catch (Exception $ex) {
                return response()->json([
                    'status' => 'error',
                    'message' => "We've got errors! " . $ex->getMessage()
                ], 500);
            }
        }
    }
}
