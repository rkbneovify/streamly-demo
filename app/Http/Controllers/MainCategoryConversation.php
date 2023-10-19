<?php

namespace App\Http\Controllers;
  
use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use DB;
use Validator;

use BotMan\BotMan\Messages\Conversations\Conversation;

class MainCategoryConversation extends Conversation
{
    

    public function MainCategoryList()
    {
        //$botman->startConversation(new TestConversation());

        // $botman->ask('Hello! have you any Question? ', function(Answer $answer) {
  
        //     $name = $answer->getText();
  
            //$this->say('Nice to meet you '.$name);
            $buttonArray = [];
            $objects = DB::table('chatboat_category')->get();
            foreach($objects as $object){                
                $button =Button::create($object->name)->value($object->name);
                $buttonArray[] = $button;
                                  
                }

                $question = Question::create('What kind of Service you are looking for?')
                            ->callbackId('select_service')
                            ->addButtons($buttonArray);
                             

                $this->ask($question, function (Answer $answer) {
                if ($answer->isInteractiveMessageReply()) {
                    $this->bot->userStorage()->save([
                        'main_category' => $answer->getValue(),
                    ]);
                    //$this->say("Oh, really! You said '".$answer->getValue()."'... is that right?");
                     $catValue =  $answer->getValue();
                     $catText  =  $answer->getText();
                     if(strtolower($catValue) == 'other'){
                        $this->bot->startConversation(new BotManConversation1());
                     }else{

                    $cat_id_arr = DB::table('chatboat_category')->where('name', '=', $catValue)->get();

                    $cat_id = $cat_id_arr[0];
                    $quelistsArray = [];

                        $que_lists = DB::table('faqs')->where('cat_id', '=', $cat_id->id)->get();

                        foreach($que_lists as $que_list){                
                            $button =Button::create($que_list->question_key)->value($que_list->id);
                            $quelistsArray[] = $button;                  
                        }

                    $question_list = Question::create($catValue)
                            ->callbackId('que_list')
                            ->addButtons($quelistsArray);

                        $this->ask($question_list, function (Answer $answer) {
                        if ($answer->isInteractiveMessageReply()) {
                            $this->bot->userStorage()->save([
                                'select_que_id' => $answer->getValue(),
                                
                            ]);
                        $que_id = $answer->getValue();
                        $que_ans_lists = DB::table('faqs')->where('id', '=', $que_id )->get();
                        foreach($que_ans_lists as $que_ans_list){                
                                
                                $this->say('<b>'.$que_ans_list->question_key.'</b><p>'.$que_ans_list->question_answer.'</p>');
                                              
                            }

                        $this->bot->startConversation(new MenuBackConversation());
                        }
                        });
                        
                        //$this->say("Oh, really! You said '".$answer->getText()."'... is that right?");
                    }
               } else {
                  $this->say("Sorry, I did not understand these commands. Here is a list of commands I understand: ...");
              }
            });
        //});
    }

    
    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
        $this->MainCategoryList();
    }
}
