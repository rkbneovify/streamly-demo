<?php

namespace App\Http\Controllers;
  
use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use BotMan\BotMan\Messages\Conversations\Conversation;
use Validator;
use DB;
use Mail;



class ImageConversation extends Conversation
{
    
   public function run()
        {
            $this->UploadImageUrl();
        }

        public function UploadImageUrl()
        {   
            
            $this->bot->ask("What Is Image URL ?", function(Answer $answer)
            {
                $user = $this->bot->userStorage()->find();
                $value=$answer->getText();
                $image_type_check = @exif_imagetype($answer->getText());
                if ($image_type_check > 0 && $image_type_check !='') {
                    //echo "image exists<br>";
                } else {
                    $this->say("Upload url not valid");
                    //$this->bot->typesAndWaits(10);
                    $this->bot->startConversation(new ImageConversation());
                    return false;
                    
                }
                $this->bot->userStorage()->save([
                        'img_url' => $answer->getText(),
                    ]);
                
                $attachment = new Image($value, [
                                                    'custom_payload' => true,
                                                ]);
                $message = OutgoingMessage::create('This is my Image')
                            ->withAttachment($attachment);

                 $this->say($message);
                 $this->say("Thank You for share image we will reply to as soon as possible.");
                //echo "<pre>";print_r($user); exit;
                $your_name = $user['your_name'];
                $email = $user['email'];
                $first_ask_que = $user['first_ask_que'];
                $main_category = $user['main_category'];
                $describe_questions = $user['describe_questions'];
                $img_url = $answer->getText();

                 $values = ['name'=>$your_name,'email'=>$email,'category'=>$main_category,'describe_questions'=>$describe_questions,'img_url'=>$img_url];
                   
                DB::table('chat_history')->insert($values);
                
                $mail_data = ['your_name'=>$your_name,'email'=>$email,'first_ask_que'=>$first_ask_que,'main_category'=>$main_category,'describe_questions'=>$describe_questions,'img_url'=>$img_url];
                
                $MainConversationController = new MainConversationController();
                $MainConversationController->html_email($mail_data);
                 if($message){
                    $this->bot->startConversation(new FurtherConversation());
                 }

            });
                         
        }
        
}
