<?php

namespace App\Listeners;

use App\Events\TelegramSendNotification;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Http;

class TelegramSendNotificationListener implements ShouldQueue
{
    use InteractsWithQueue;
    
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
    public function handle(TelegramSendNotification $event): void
    {
        $birthday = $event->candidate->birthday;
        $age = Carbon::parse($birthday)->age;
        $resume = $event->resume ?? null;
        if($resume !== null){
            $month  = $resume->calculate_experience($resume->data);
            if($month > 0){
                $yaerMonth = $this->convertMonthsToYearsAndMonths($month);
            } else 
            {
                $yaerMonth = "Ish tajribasi yo'q";
            }
        }
       
        $message = "⚡️ <b> Yangi ariza </b> \n\n";
      
        $message .= "📜 Vakansiya: <b>" . $event->job->title . "</b> \n";
        if($event->candidate->sex == 'male'){
            $message .= "👨‍💼 Nomzod: <b>" . $event->candidate->name . " " . $event->candidate->surname . "</b> \n";
        } else {
            $message .= "🧑‍💼 Nomzod: <b>" . $event->candidate->name . " " . $event->candidate->surname . "</b> \n";
        }

        if($resume !== null){
            $message .= "💼 Mutaxassis: <b>" . $event->resume->data['position'] . "</b> \n";
            $message .= "👨‍💻 Ish tajribasi: <b>" . $yaerMonth . "</b> \n";
        }

        $message .= "🗓 Yoshi: <b>" . $age . "</b> \n";
        if($event->message !== null){
            $message .= "✍️ Qo'shimcha xabar: <b>" . $event->message  . "</b> \n\n";
        } else {
            $message .= "✍️ Qo'shimcha xabar:<b> Yo'q</b> \n\n";
        }
       
        foreach($event->customer->telegram_id as $value){
            Http::withoutVerifying()->post("https://api.telegram.org/bot".env('TELEGRAM_BOT_NOTIFICATION') ."/sendMessage", [
                    'chat_id' => $value,
                    'text' => $message,
                    'parse_mode' => 'HTML',
                    'reply_markup' => json_encode([
                        'inline_keyboard' => [[
                            [
                                'text' => "↗️ Chatga o'tish",
                                'url' => 'https://jobo.uz/user/chat/' . $event->chat_id
                            ]
                        ]]
                    ])
                ]);
            }
            
    }

    
    public  function convertMonthsToYearsAndMonths($months) {
        $years = floor($months / 12);
        $remainingMonths = $months % 12;
        $result = "";
        if($years > 0){
            $result = "$years yil ";
        }
       
        if ($remainingMonths > 0) {
            $result .= "$remainingMonths oy";
        }
      
        return $result;
    }
}
