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



class FurtherConversation extends Conversation
{
    
    public function FurtherStep()
{
  
    $question = Question::create("May I Help You Further...?")
                ->fallback("Unable to help at this time, please try again later")
                ->callbackId("further_query")
                ->addButtons([
                    Button::create("Yes")->value("yes"),
                    Button::create("No")->value("no"),
                ]);
            $this->ask($question, function (Answer $answer) {
                if ($answer->isInteractiveMessageReply()) {
                    switch ($answer->getValue()) {
                        case "yes":
                            
                        $this->bot->startConversation(new MainCategoryConversation());
                        
                            break;
                        case "no":
                            
                            $this->say('Thanks For Reaching Out To Us...!<br/>Have A Nice Day..!');
                             
                            break;
                    }
                }
            });
}
    

    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
        $this->FurtherStep();
    }
}
