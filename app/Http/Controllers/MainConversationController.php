<?php  
namespace App\Http\Controllers;
  
use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use BotMan\BotMan\Messages\Incoming\Answer;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;
use DB;
use Mail;
  
class MainConversationController extends Controller
{
    /**
     * Place your BotMan logic here.
     */

    // public function testbotman()
    // {
    //     return view('botman.test');
    // }

    public function handle()
    {
        $botman = app('botman');
  
        $botman->hears('{message}', function($botman, $message) {
  
            if (strtolower($message) == 'hi' OR strtolower($message) == 'hello') {
                
                $botman->startConversation(new TestConversation());
                
            }else{
                $botman->reply("write 'hi' for testing...");
            }
  
        });
  
        $botman->listen();
    }


    public function html_email(array $mail_data) {

        $name = $mail_data['your_name'];
        $email = $mail_data['email'];
        $first_ask_que = $mail_data['first_ask_que'];
        $describe_questions = $mail_data['describe_questions'];
        $img_url = $mail_data['img_url'];
            $data = array(
            'name' => $name,
            'first_ask_que' => $first_ask_que,
            'describe_questions' => $describe_questions,
            'email' => $email,
            'img_url'=>$img_url
           
        );
        //Send the email
         Mail::send('emails.chatboat', $data, function( $message ) use ($data)
        {
            $message->to('contact.streamlytv@yopmail.com', $data['name'])
                ->from('contact@streamlytv.com', 'StreamlyTv')
                ->subject('Thank you for Chat with StreamlyTv');
               if($data['img_url'] !=''){ 
                    $message->attach($data['img_url']);
                   
                }   
        });

        Mail::send('emails.ChatboatUser', $data, function( $message ) use ($data)
        {
            $message->to($data['email'], $data['name'])
                ->from('contact@streamlytv.com', 'StreamlyTv')
                ->subject('Thank you for Chat with StreamlyTv');
                 
        });
    }
 

}