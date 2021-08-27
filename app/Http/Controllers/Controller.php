<?php

namespace App\Http\Controllers;

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

        $rate  = Rate::where('admin_id', $player->group->id)->first();

        if(!$rate)
            $rate  = Rate::where('admin_id', 1)->first();

        if($resultApi && isset($resultApi['total_slp'])){
            $dailyYesterday = count($player->totalSLP)== 0 ? 0 : $player->totalSLP[0]->total;
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
                        'totalPlayer'   => $totaldaily <= $rate->lessSlp ? $totaldaily - (($totaldaily * $rate->lessPercentage) / 100) : $totaldaily - (($totaldaily * $rate->greaterPercentage) / 100), 
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
                        'totalPlayer'   => $totaldaily <= $rate->lessSlp ? $totaldaily - (($totaldaily * $rate->lessPercentage) / 100) : $totaldaily - (($totaldaily * $rate->greaterPercentage) / 100), 
                    ]
                );
                
        }

    }
}
