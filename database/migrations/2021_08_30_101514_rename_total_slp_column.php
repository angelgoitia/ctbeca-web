<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameTotalSlpColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('total_slps', function(Blueprint $table) {
            $table->renameColumn('totalPlayer', 'totalManager');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('total_slps', function(Blueprint $table) {
            $table->renameColumn('totalManager', 'totalPlayer');
        });
    }
}
