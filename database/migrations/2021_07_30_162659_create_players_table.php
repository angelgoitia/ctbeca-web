<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string('name',50);
            $table->string('email',100)->unique();
            $table->string('phone',20);
            $table->string('telegram',50);
            $table->string('reference', 50);
            $table->string('wallet',100)->unique();
            $table->string('urlCodeQr')->nullable();
            $table->string('emailGame',50)->unique();
            $table->string('passwordGame',100);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('players');
    }
}
