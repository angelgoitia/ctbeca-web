<?php

namespace App\Console\Commands;

use App\Player;
use App\TotalSlp;
use Carbon\Carbon;
use Illuminate\Console\Command;

class Axies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:Axies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Axies';

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

        $players = Player::with('group')->with(['totalSLP' => function($q) use($now) {
            $q->where('date', "!=", $now)->orderBy('date','DESC'); 
        }])->get();

        foreach ($players as $player)
        {
            app('App\Http\Controllers\AdminController')->getUpdateAnimal($player);
            
        }

        $this->info('Se ha actualizado correctamente');
            
    }
}
