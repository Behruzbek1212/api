<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class DbBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Database backup  command';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filename = 'jobo_next' . time() . rand() . '.sql';
        try{
            $command = env('Db_MYSQLDUMP') . "  --user=" . env('DB_USERNAME') . " --password=" . env("DB_PASSWORD") .
                " --host=" . env('DB_HOST') . " " . env('DB_DATABASE') . " > " . public_path('backup/' . $filename);

            shell_exec($command);
            $telegramBotToken = env('TELEGRAM_TOKEN_BACKUP');
            $chatId = env('TELEGRAM_BACKUP_CHANNEL_ID');

            $telegramApiUrl = "https://api.telegram.org/bot{$telegramBotToken}/sendDocument";

            $curlFile = new \CURLFile(public_path('backup/' . $filename));

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $telegramApiUrl);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, [
                'chat_id' => $chatId,
                'document' => $curlFile,
            ]);
            curl_exec($curl);
            curl_close($curl);
        } 
         catch(Exception $e)
        {
            return response()->json([
                'status' =>  false,
                'message'=> $e->getMessage(),
              
            ]);
        }
        

        
        
    
    }
}
