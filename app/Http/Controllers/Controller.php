<?php

namespace App\Http\Controllers;

use App\Claim;
use App\Player;
use App\Rate;
use App\TotalSlp;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function updateSlpPlayers($players){
        foreach($players as $player){
            app('App\Http\Controllers\Controller')->updateSlp($player);
        }
    }

    public function updateSlp($player){
        $now = Carbon::now();
        $cutHours = Carbon::parse($now->year.'-'.$now->month.'-'.$now->day.' 20:00:00');

        $url = "https://api.lunaciarover.com/stats/0x".$player->wallet;
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        
        $resultApi = json_decode(curl_exec($ch), true);
        curl_close($ch); 
        
        $rate = null;

        if($player->group)
            $rate  = Rate::where('admin_id', $player->group->id)->first();

        if(!$rate)
            $rate  = Rate::where('admin_id', 1)->first();

        if($resultApi && isset($resultApi['total_slp'])){
            $last_claim = Carbon::createFromTimestamp($resultApi['last_claim_timestamp'])->format('Y-m-d');
            
            if(count($player->totalSLP) == 0)
                $dailyYesterday = 0;
            else{
                if($player->totalSLP[0]->total > intval($resultApi['total_slp']))
                    $dailyYesterday = 0;
                else
                    $dailyYesterday = $player->totalSLP[0]->total;
            }
            
            $totaldaily = intval($resultApi['total_slp']) - $dailyYesterday;

            if($now < $cutHours)
                TotalSlp::updateOrCreate(
                    [
                        'player_id'     => $player->id,
                        'date'          => $now->format('Y-m-d'),
                    ],
                    [
                        'total'         => intval($resultApi['total_slp']),
                        'daily'         => $totaldaily,
                        'totalManager'   => $totaldaily <= $rate->lessSlp ? ($totaldaily - ($totaldaily * $rate->lessPercentage) / 100) : ($totaldaily - ($totaldaily * $rate->greaterPercentage) / 100), 
                    ]
                );
            else
                TotalSlp::updateOrCreate(
                    [
                        'player_id'     => $player->id,
                        'date'          => $now->addDay()->format('Y-m-d'),
                    ],
                    [
                        'total'         => intval($resultApi['total_slp']),
                        'daily'         => $totaldaily,
                        'totalManager'   => $totaldaily <= $rate->lessSlp ? ($totaldaily - ($totaldaily * $rate->lessPercentage) / 100) : ($totaldaily - ($totaldaily * $rate->greaterPercentage) / 100), 
                    ]
                );
            
            app('App\Http\Controllers\Controller')->claimPlayer($player->id, $last_claim);
            $player->dateClaim = $last_claim;
            $player->save();
                
        }

    }

    public function getPriceSlp(){
        $urlApi = [
            'https://api.binance.com', 'https://api1.binance.com', 'https://api2.binance.com', 'https://api3.binance.com'
        ];

        foreach ($urlApi as $api){

            $ch = curl_init($api.'/api/v3/ticker/price?symbol=SLPUSDT');
            
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array( 
                "Content-Type: application/json",
                "X-Requested-With: XMLHttpRequest",
            ));

            $resultApi = json_decode(curl_exec($ch), true);
            curl_close($ch);

            if($resultApi && $resultApi['symbol'] == 'SLPUSDT'){
                return floatval($resultApi['price']);
            }
           
        }

        return 0;
    }

    public function claimPlayer($playerId, $last_claim){
        $startDate = Carbon::now()->setDay(1)->format('Y-m-d');
        $endDate = Carbon::now()->setDay(15)->format('Y-m-d');
        $totalPlayer = 0;
        $totalManager = 0;
        
        if(Carbon::parse($last_claim)->format('d') > 15){
            $startDate = Carbon::parse($last_claim)->setDay(15)->format('Y-m-d');
            $endDate = Carbon::parse($last_claim)->endOfMonth()->format('Y-m-d');
        }

        $player = Player::whereId($playerId)->with(['totalSLP' => function($q) use($startDate, $endDate) {
            $q->whereDate('date', ">=",$startDate)
                ->whereDate('date', "<=",$endDate);
        }])->first();

        foreach($player->totalSLP as $slp){
            $totalPlayer += ($slp-> daily - $slp-> totalManager);
            $totalManager += $slp-> totalManager;
        }

        $totalClaim = $totalManager + $totalPlayer;

        Claim::updateOrCreate(
            [
                'player_id'     => $player->id,
                'date'          => $last_claim,
            ],
            [
                'total'         => $totalClaim,
                'totalPlayer'   => $totalPlayer,
                'totalManager'  => $totalManager,
            ]
        );
    }
}
