<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSttDontiep extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('red_stt_dontiep', function (Blueprint $table) {
            $table->increments('id');
            $table->string('loai_stt', 2);
            $table->integer('sothutunumber');
            $table->integer('trangthai');
            $table->dateTimeTz('ngayphat');
            $table->dateTimeTz('ngaygoi');
            $table->string('khuvuc', 2);
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('red_stt_dontiep');
    }
}
