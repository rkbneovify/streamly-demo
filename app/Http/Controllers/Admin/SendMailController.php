<?php  
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Mail;
  
class SendMailController extends Controller
{

    public function handleRequest(Request $request)
    {
         //echo "<pre>";print_r($_POST); exit;
         $name = $request->input('your_name');
         $chat_id = $request->input('chat_id');
         $email = $request->input('email');
         $ask_que = $request->input('ask_que');
         $category = $request->input('category');
         $describe_questions = $request->input('describe_questions');
         $reply_answer = $request->input('reply_answer');
         $img_url = $request->input('img_url');

         $values = ['chat_id'=>$chat_id,'question_reply'=>$reply_answer];
                   
         DB::table('chat_answer')->insert($values);
         
        if($email !=''){

        $mail_data = ['your_name'=>$name,'email'=>$email,'first_ask_que'=>$ask_que,'main_category'=>$category,'describe_questions'=>$describe_questions,'img_url'=>$img_url,'reply_answer'=>$reply_answer];
        //echo "<pre>";print_r($mail_data); exit;
        $mail_send = $this->html_email($mail_data);
        
            if($mail_send == false){
                return response()->json(['error' => false,'msg'=>'mail send sucessfully']);

            }else{
                return response()->json(['error' => true,'msg'=>'error in send mail']);
            }

        }else{

            return response()->json(['error' => true,'msg'=>'you have no any sender email id !']);

        }
    }


    public function html_email(array $mail_data) {

        $name = $mail_data['your_name'];
        $email = $mail_data['email'];
        $first_ask_que = $mail_data['first_ask_que'];
        $describe_questions = $mail_data['describe_questions'];
        $reply_answer = $mail_data['reply_answer'];
        $img_url = $mail_data['img_url'];
            $data = array(
            'name' => $name,
            'first_ask_que' => $first_ask_que,
            'describe_questions' => $describe_questions,
            'email' => $email,
            'reply_answer' => $reply_answer,
            'img_url'=>$img_url
           
        );

        // Send the email
        Mail::send('admin.email.chatboat', $data, function( $message ) use ($data)
        {
            $message->to($data['email'], $data['name'])
                ->from('contact@streamlytv.com', 'StreamlyTv')
                ->subject('Thank you for Chat with StreamlyTv');
               if($data['img_url'] !=''){ 
                    $message->attach($data['img_url']);
                   
                }   
        });


            if(count(Mail::failures()) > 0){
                $response = true;
            }else{
               $response = false; 
            }
        return $response;
    }


    public function AnswerHistory(Request $request)
    {
         
         $chat_id = $request->input('chat_id');

         $chat_answer = DB::table('chat_answer')
                  ->where('chat_id', '=', $chat_id)
                  ->orderBy('id', 'DESC')
                  ->get();
        $chat_que = DB::table('chat_history')
                  ->where('id', '=', $chat_id)
                  ->get();

        $que_title = $chat_que[0]->describe_questions;

         $returnHTML =  view('admin.chat_answer.index',['chat_answer' => $chat_answer])->render();

         $count = count($chat_answer);

         if($count > 0){
          return response()->json(array('error' => false,'que_title'=>$que_title,'html'=>$returnHTML));
         }else{
          return response()->json(array('error' => false, 'que_title'=>$que_title,'html'=>"no any reply answer yet")); 
         }

         
           
    }
 

}