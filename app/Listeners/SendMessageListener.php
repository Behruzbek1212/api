<?php

namespace App\Listeners;

use App\Events\SendMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Http;

class SendMessageListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SendMessage $event): void
    {
        ;
        $message = "⚡️ <b> Yangi xabar </b> \n\n";
        $message .= "🏢 Kampaniya: <b>" . $event->customer->name . "</b> \n";
        $message .= "📜 Vakansiya: <b>" . $event->job->title . "</b> \n";
        $message .= "✍️ Xabar: <b>" . $event->message->message  . "</b> \n";

       if($event->candidate->telegram_id !== null && $event->candidate->telegram_id !== []){
        foreach($event->candidate->telegram_id as $value){
            Http::withoutVerifying()->post("https://api.telegram.org/bot".env('TELEGRAM_BOT_NOTIFICATION') ."/sendMessage", [
                    'chat_id' => $value,
                    'text' => $message,
                    'parse_mode' => 'HTML',
                    'reply_markup' => json_encode([
                        'inline_keyboard' => [[
                            [
                                'text' => "↗️ Chatga o'tish",
                                'url' => 'https://jobo.uz/user/chat/' . $event->chat->id
                            ]
                        ]]
                    ])
                ]);
            }
       }
        
    }
}
