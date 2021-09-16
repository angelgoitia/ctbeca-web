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
        /* Update SLP Daily*/
        $listPlayer = array();
        $status = 0;
        $now = Carbon::now()->format('Y-m-d');
        $players = Player::with('group')->with(['totalSLP' => function($q) use($now) {
            $q->where('date', "!=", $now)->orderBy('date','DESC'); 
        }])->get();

        foreach ($players as $player)
        {
            $rate = null;

            if($player->group)
                $rate  = Rate::where('admin_id', $player->group->id)->first();

            if(!$rate)
                $rate  = Rate::where('admin_id', 1)->first();

            for($i = 0; $i <= 1; $i++){
                if($i == 0){
                    $url = "https://api.lunaciarover.com/stats/0x".$player->wallet;
                }else{
                    $url = "https://game-api.skymavis.com/game-api/clients/0x".$player->wallet."/items/1";
                }

                $ch = curl_init($url);
            
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
                
                $resultApi = json_decode(curl_exec($ch), true);
                curl_close($ch); 

                if($resultApi && isset($resultApi['total_slp'])){
                    $last_claim = Carbon::createFromTimestamp($resultApi['last_claim_timestamp'])->format('Y-m-d');
                    $total_slp = intval($resultApi['total_slp']);
                    break;
                }else if($resultApi && isset($resultApi['total'])){
                    $last_claim = Carbon::createFromTimestamp($resultApi['last_claimed_item_at'])->format('Y-m-d');
                    $total_slp = intval($resultApi['total']);
                    break;
                }
            }
            
            if(isset($last_claim) && isset($total_slp)){
                $dailyYesterday = count($player->totalSLP)== 0 ? 0 : $player->totalSLP[0]->total;
                $totaldaily = $total_slp - $dailyYesterday;
                
                TotalSlp::updateOrCreate(
                    [
                        'player_id'     => $player->id,
                        'date'          => $now,
                    ],
                    [
                        'total'         => $total_slp,
                        'daily'         => $totaldaily,
                        'totalManager'   => $totaldaily <= $rate->lessSlp ? ($totaldaily - ($totaldaily * $rate->lessPercentage) / 100) : ($totaldaily - ($totaldaily * $rate->greaterPercentage) / 100), 
                    ]
                );

                $date = Carbon::parse($player->dateClaim);
                $now = Carbon::now();
                $day = 15;

                if (Carbon::now()->format('d') > 15 && Carbon::now()->endOfMonth()->format('d') == 28)
                    $day = 13;

                $diff = $date->diffInDays($now);

                if($diff >= $day){
                    app('App\Http\Controllers\Controller')->claimPlayer($player->id, $player->dateClaim);
                }
                
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
