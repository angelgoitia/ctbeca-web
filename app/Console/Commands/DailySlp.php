<?php

namespace App\Console\Commands;

use App\Player;
use App\TotalSlp;
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
        $players = Player::with('lastSLP')->get();

        foreach ($players as $player)
        {
            $url = "https://api.lunaciarover.com/stats/0x".$player->wallet;
            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            
            $resultApi = json_decode(curl_exec($ch), true);
            curl_close($ch); 

            if($resultApi && isset($resultApi['total_slp'])){
                $dailyYesterday = empty($player->lastSLP)? 0 : $player->lastSLP->total;
                $totaldaily = intval($resultApi['total_slp']) - intval($resultApi['total_slp']);
                
                TotalSlp::create([
                    'player_id' => $player->id,
                    'total' => intval($resultApi['total_slp']),
                    'daily' => $totaldaily,
                ]);

                $status++;
            }
            
        }

        if(count($players) == $status){
            $this->info('El corte se ha realizado correctamente');
        }else{
            $this->info('El corte se ha realizado '.$status.' de '.count($players).' Becados');
        }
            
    }
}
