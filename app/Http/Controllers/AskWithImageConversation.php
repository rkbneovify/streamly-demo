<?php

namespace App\Http\Controllers;
  
use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Conversations\Conversation;
use Validator;
use DB;
use Mail;



class AskWithImageConversation extends Conversation
{
    
   public function run()
        {
            $this->AskWithImage();
        }

        public function AskWithImage()
        {   
            $question_img = Question::create('Want To Upload Pic?')
                                ->callbackId('agree')
                                ->addButtons([
                                    Button::create('Yes')->value('yes'),
                                    Button::create('No')->value('no'),
                        ]);

                $this->ask($question_img, function (Answer $answer) {
                    if ($answer->isInteractiveMessageReply()) {

                        if($answer->getValue() == 'yes') {
                                    
                            $this->bot->startConversation(new ImageConversation());
                                    
                         }else{

                            $user = $this->bot->userStorage()->find();

                            $your_name = $user['your_name'];
                            $email = $user['email'];
                            $first_ask_que = $user['first_ask_que'];
                            $main_category = $user['main_category'];
                            $describe_questions = $user['describe_questions'];

                            $values = ['name'=>$your_name,'email'=>$email,'category'=>$main_category,'describe_questions'=>$describe_questions,'img_url'=>''];
                   
                            DB::table('chat_history')->insert($values);

                            $mail_data = ['your_name'=>$your_name,'email'=>$email,'first_ask_que'=>$first_ask_que,'main_category'=>$main_category,'describe_questions'=>$describe_questions,'img_url'=>''];
                
                            $MainConversationController = new MainConversationController();
                            $MainConversationController->html_email($mail_data);
                            
                             $question = Question::create("Are you continue with ask query ?")
                                ->fallback("Unable to help at this time, please try again later")
                                ->callbackId("continue_query")
                                ->addButtons([
                                    Button::create("Yes")->value("yes"),
                                    Button::create("No")->value("no"),
                                ]);
                                $this->ask($question, function (Answer $answer) {
                                    if ($answer->isInteractiveMessageReply()) {
                                        switch ($answer->getValue()) {
                                            case "yes":
                                                $this->bot->startConversation(new BotManConversation1());
                                                break;
                                            case "no":
                                                $this->bot->startConversation(new FurtherConversation());
                                                break;
                                            }
                                        }
                                });
                        }
                    }
                });
            
        }

        
}
