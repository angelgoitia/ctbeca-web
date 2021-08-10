<?php

namespace App\Console\Commands;

use App\Player;
use App\TotalSlp;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DailySlp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:DailySlp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Daily SLP';

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
        $status = 0;
        $now = Carbon::now()->format('Y-m-d');
        $players = Player::with(['totalSLP' => function($q) use($now) {
            $q->where('date', "!=", $now)->orderBy('created_at','DESC'); 
        }])->get();

        foreach ($players as $player)
        {
            $url = "https://api.lunaciarover.com/stats/0x".$player->wallet;
            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            
            $resultApi = json_decode(curl_exec($ch), true);
            curl_close($ch); 

            if($resultApi && isset($resultApi['total_slp'])){
                $dailyYesterday = empty($player->totalSLP)? 0 : $player->totalSLP[0]->total;
                $totaldaily = intval($resultApi['total_slp']) - $dailyYesterday;
                
                TotalSlp::updateOrCreate(
                    [
                        'player_id'     => $player->id,
                        'date'          => Carbon::now()->format('Y-m-d'),
                    ],
                    [
                        'player_id' => $player->id,
                        'total'     => intval($resultApi['total_slp']),
                        'daily'     => $totaldaily,
                    ]
                );

                $status++;
            }

            app('App\Http\Controllers\AdminController')->getUpdateAnimal($player->id, $player->wallet);
            
        }

        if(count($players) == $status){
            $this->info('El corte se ha realizado correctamente');
        }else{
            $this->info('El corte se ha realizado '.$status.' de '.count($players).' Becados');
        }
            
    }
}
