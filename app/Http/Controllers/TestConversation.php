<?php

namespace App\Http\Controllers;
  
use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use Validator;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;
use DB;

class TestConversation extends Conversation
{
    
    public function askName()
    {
        $this->ask('What is your good name?', function (Answer $answer) {
            $this->bot->userStorage()->save([
                'your_name' => $answer->getText(),
            ]);

            $this->say('Nice to meet you '.$answer->getText());
            $this->bot->typesAndWaits(1);
            $this->askEmail();
        });
    }

    
    public function askEmail()
    {
        $this->ask('What is your email?', function (Answer $answer) {
            $validator = Validator::make(['email' => $answer->getText()], [
                'email' => 'email',
            ]);

            if ($validator->fails()) {
                
                $this->say('That doesn\'t look like a valid email. Please enter a valid email.');
                $this->bot->typesAndWaits(1);
                $this->askEmail();

            }else{

                $this->bot->userStorage()->save([
                    'email' => $answer->getText(),
                ]);

                $this->FirstaskQuestion();

            }
            
        });
    }


    public function FirstaskQuestion()
    {
        $this->ask('Hello! What can I help you with today? ', function(Answer $answer) {
                $this->bot->userStorage()->save([
                    'first_ask_que' => $answer->getText(),
                ]);

                $this->bot->startConversation(new MainCategoryConversation());

        });
    }

    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
        $this->askName();
    }
}
