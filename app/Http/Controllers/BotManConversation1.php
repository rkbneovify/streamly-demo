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



class BotManConversation1 extends Conversation
{
    
   public function run()
        {
            $this->selectHelpQuery1();
        }

        public function selectHelpQuery1()
        {   
            $this->bot->ask('please describe your questions here ? ', function(Answer $answer) {
            
            $name = $answer->getText();
            $this->bot->userStorage()->save([
                        'describe_questions' => $answer->getValue(),
                    ]);
            $user = $this->bot->userStorage()->find();
            //echo "<pre>";print_r($user); exit;
            // $your_name = $user['your_name'];
            // $email = $user['email'];
            // $first_ask_que = $user['first_ask_que'];
            $main_category = $user['main_category'];
            // $describe_questions = $user['describe_questions'];
            // $mail_data = ['your_name'=>$your_name,'email'=>$email,'first_ask_que'=>$first_ask_que,'main_category'=>$main_category,'describe_questions'=>$describe_questions];
            // $MainConversationController = new MainConversationController();
            // $MainConversationController->html_email($mail_data);
            // $this->bot->typesAndWaits(1);
            $chatboat_category = DB::table('chatboat_category')->where('name','=', $main_category)->get();
            $cat_id = $chatboat_category[0]->id;
            $arr_search = explode(" ",$name);
            $faq = DB::table('faqs');
                  //->Where('question_key', 'like', '%' . $name . '%')
              foreach($arr_search as $word){
              $faq->Where('question_key', 'LIKE', '%'.$word.'%');
              }
              $faq = $faq->where('cat_id','=', $cat_id);
              $faq = $faq->get();
                 
                if(count($faq) > 0){ 

                    $question_answer = $faq[0]->question_answer;
                    $this->say($question_answer);

                    $your_name = $user['your_name'];
                    $email = $user['email'];
                    $first_ask_que = $user['first_ask_que'];
                    $main_category = $user['main_category'];
                    $describe_questions = $user['describe_questions'];
                    
                    $mail_data = ['your_name'=>$your_name,'email'=>$email,'first_ask_que'=>$first_ask_que,'main_category'=>$main_category,'describe_questions'=>$describe_questions,'img_url'=>''];

                    $MainConversationController = new MainConversationController();
                    $MainConversationController->html_email($mail_data);

                    $this->bot->startConversation(new FurtherConversation());

                }else{

                    $this->say("I don't understand <br/>Please Ask Someone Else I Can Help You With....!");

                    $this->bot->startConversation(new AskWithImageConversation());
                    //echo "<pre>";print_r($user); 
                }
                //echo "<pre>";print_r($user);
                // $MainConversationController = new MainConversationController();
                // $MainConversationController->html_email($mail_data);
                // $this->bot->typesAndWaits(1);   
            });
            
        }

        
}
