<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\BugReporting;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
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
    public function faq(Request $request)
    {
        $faq = DB::table('faqs')->select('question_key', 'question_answer')->get();
        return response()->json([
            'status' => 'success',
            'message' => 'FAQ received successfully.',
            'data' => $faq
        ], 200);
    }

    public function sendBugReportAPI(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'email_input' => 'required|email',
            'bug_severity' => 'required|string',
            'occurrence' => 'required|string',
            'platform_info' => 'required|string',
            'details' => 'required|string',
            // 'g-recaptcha-response' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => 'validation_error',
                'message' => $validator->errors()
            ], 400);
        } else {
            $mailToAddress = env('MAIL_TO_ADDRESS');

            // Validate MAIL_TO_ADDRESS environment variable
            if (!filter_var($mailToAddress, FILTER_VALIDATE_EMAIL)) {
                return response()->json(['error' => 'Invalid mail to address configuration.'], 500);
            }

            $html = '<html><br>Error Report of ' . env('APP_NAME') . '.
            <br><br>
            User Email: ' . $request['email_input'] . '.<br><br>
            Bug Severity: ' . $request['bug_severity'] . '.<br>
            Occurrence: ' . $request['occurrence'] . '.<br>
            Platform info: ' . $request['platform_info'] . '.<br>
            Details: ' . $request['details'] . '.<br>
            </html>';

            try {
                Mail::send('emails.general', ["html" => $html], function ($message) use ($request, $mailToAddress) {
                    $message->to($mailToAddress, env('APP_NAME'))
                        ->subject(env('APP_NAME') . ': Feedback/Bug Report');
                });

                $bugReported = BugReporting::create([
                    'email' => $request['email_input'],
                    'occurences' => $request['occurrence'],
                    'bug_severity' => $request['bug_severity'],
                    'platform' => $request['platform_info'],
                    'message' => $request['details'],
                ]);

                if ($bugReported) {
                    return response()->json([
                        "success" => true,
                        "response_code" => 200,
                        'message' => 'Bug Report Sent!'
                    ], 200);
                } else {
                    return response()->json([
                        "success" => false,
                        "response_code" => 500,
                        'message' => 'Something Went Wrong!'
                    ], 200);
                }
            } catch (Exception $ex) {
                return response()->json(['error' => "We've got errors!" . json_encode($ex->getMessage(), true)], 500);
            }
        }
    }
}
