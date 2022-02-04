<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use BotMan\BotMan\BotMan;

use GuzzleHttp\Client;
use BotMan\BotMan\Messages\Incoming\Answer;

use App\Conversations\OnboardingConversation;
class BotManController extends Controller
{
    public function handle()
    {
    	$botman = app('botman');
        
        // Simple respond method
        $botman->hears('Hello|Hi', function (BotMan $bot) {
            $this->startConversation($bot);
        });
      
        $botman->hears('Give me {currency} rates', function ($bot, $currency) {
            $bot->types();
            $results = $this->getCurrency($currency);
            $bot->reply($results);
        });
      
        $botman->fallback(function($bot) {
            $bot->types();
            $bot->reply('Sorry, I did not understand these commands. Please retype again...Try "Hi" or "Hello" ');
        });
      
        $botman->listen();
    }

    public function getCurrency($currency)
    {
        return "results".$currency;
    }

    /**
     * Loaded through routes/botman.php.
     *
     * @param BotMan $bot
     */
    public function startConversation(BotMan $bot)
    {
        $bot->startConversation(new OnboardingConversation());
    }
}
