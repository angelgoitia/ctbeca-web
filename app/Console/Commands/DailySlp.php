<?php

namespace App\Console\Commands;

use App\Notifications\InfoGroup;
use App\Player;
use App\Rate;
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
        $listPlayer = array();
        $status = 0;
        $now = Carbon::now()->format('Y-m-d');
        $players = Player::with('group')->with(['totalSLP' => function($q) use($now) {
            $q->where('date', "!=", $now)->orderBy('date','DESC'); 
        }])->get();

        foreach ($players as $player)
        {
            $url = "https://api.lunaciarover.com/stats/0x".$player->wallet;
            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            
            $resultApi = json_decode(curl_exec($ch), true);
            curl_close($ch); 

            $last_claim = Carbon::createFromTimestamp($resultApi['last_claim_timestamp'])->format('Y-m-d');
            $now = Carbon::now()->format('Y-m-d');

            $rate  = Rate::where('admin_id', $player->group->id)->first();

            if(!$rate)
                $rate  = Rate::where('admin_id', 1)->first();

            if($resultApi && isset($resultApi['total_slp']) && $now == $last_claim){
                $dailyYesterday = count($player->totalSLP)== 0 ? 0 : $player->totalSLP[0]->total;
                $totaldaily = intval($resultApi['last_claim_amount']) - $dailyYesterday;
                
                $player->dateClaim = $now;
                $player->save();

                TotalSlp::updateOrCreate(
                    [
                        'player_id'     => $player->id,
                        'date'          => $now,
                    ],
                    [
                        'total'         => intval($resultApi['total_slp']),
                        'daily'         => $totaldaily,
                        'totalPlayer'   => $totaldaily <= $rate->lessSlp ? $totaldaily - (($totaldaily * $rate->lessPercentage) / 100) : $totaldaily - (($totaldaily * $rate->greaterPercentage) / 100), 
                    ]
                );
                $status++;
            }else if($resultApi && isset($resultApi['total_slp'])){
                $dailyYesterday = count($player->totalSLP)== 0 ? 0 : $player->totalSLP[0]->total;
                $totaldaily = intval($resultApi['total_slp']) - $dailyYesterday;
                TotalSlp::updateOrCreate(
                    [
                        'player_id'     => $player->id,
                        'date'          => $now,
                    ],
                    [
                        'total'         => intval($resultApi['total_slp']),
                        'daily'         => $totaldaily,
                        'totalPlayer'   => $totaldaily <= $rate->lessSlp ? $totaldaily - (($totaldaily * $rate->lessPercentage) / 100) : $totaldaily - (($totaldaily * $rate->greaterPercentage) / 100),
                    ]
                );
                $status++;
            }else {

                (new User)->forceFill([
                    'email' => $player->group->email,
                ])->notify(
                    new InfoGroup($player)
                ); 
            }

            
        }

        if(count($players) == $status){
            $this->info('El corte se ha realizado correctamente');
        }else{
            $this->info('El corte se ha realizado '.$status.' de '.count($players).' Becados');
        }
            
    }
}
