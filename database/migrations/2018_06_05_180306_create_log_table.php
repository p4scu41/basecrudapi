<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->text('message')->nullable();
            $table->unsignedTinyInteger('level')->nullable();
            $table->string('level_name')->nullable();
            $table->json('context')->nullable();
            $table->string('channel')->nullable();
            $table->json('extra')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('url')->nullable();
            $table->string('method')->nullable();
            $table->string('ip')->nullable();
            $table->json('data')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('php_sapi_name')->nullable();
            $table->string('user_process')->nullable();
            $table->timestamp('created_at')->useCurrent()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log');
    }
}
