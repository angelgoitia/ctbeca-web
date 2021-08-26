<?php

namespace App\Console\Commands;

use App\Player;
use App\Notifications\Reminder;
use Carbon\Carbon;
use Illuminate\Console\Command;

class Notice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:Notice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notification Notice';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        if(Carbon::now()->format('d') == 15 || Carbon::now()->format('d') == Carbon::now()->endOfMonth()->format('d')){
            
            if(Carbon::now()->format('d') <= 15 || (Carbon::now()->format('d') > 15 && Carbon::now()->endOfMonth()->format('d') >28 ))
                $day = 15;
            else if (Carbon::now()->format('d') > 15 && Carbon::now()->endOfMonth()->format('d') == 28)
                $day = 13;

            $players = Player::all();
            foreach($players as $player){
                $status = false;
                $title = "Recordatorio";
                $body = "Recuerdan que debe reclamar en 10 minutos";
                $date = Carbon::parse($player->dateClaim);
                $now = Carbon::now();

                $diff = $date->diffInDays($now);
                if($diff >= $day && $player->tokenFCM){
                    
                    $status = true;
                    $url = "https://fcm.googleapis.com/fcm/send";
                    $token = $player->tokenFCM;
                    $serverKey = env('SERVER_KEY_FCM');
                    $notification = array('title' =>$title , 'body' => $body, 'sound' => 'default', 'badge' => '1');
                    $arrayToSend = array('to' => $token, 'notification' => $notification, 'priority'=>'high');
                    $json = json_encode($arrayToSend);
                    $headers = array();
                    $headers[] = 'Content-Type: application/json';
                    $headers[] = 'Authorization: key='. $serverKey;
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                    //Send the request
                    $response = curl_exec($ch);

                }else if($diff >= $day){
                    $status = true;
                }

                if($status)
                    (new User)->forceFill([
                        'email' => $player->email,
                    ])->notify(
                        new Reminder($player, $body)
                    ); 
                    
            }

            $this->info('Se ha enviado las notificaciones correctamente');

        }else
            $this->info('La fecha actual no corresponde a los quincenal y últimos');
            
    }
}
