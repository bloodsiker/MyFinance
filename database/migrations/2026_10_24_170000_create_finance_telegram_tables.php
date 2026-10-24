<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinanceTelegramTables extends Migration
{
    public function up()
    {
        Schema::create('finance_telegram_chats', function (Blueprint $table) {
            $table->bigInteger('chat_id')->primary();
            $table->unsignedBigInteger('user_id');
            $table->json('state');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        Schema::create('finance_telegram_updates', function (Blueprint $table) {
            $table->bigInteger('update_id')->primary();
            $table->bigInteger('chat_id')->index();
            $table->json('reply');
            $table->timestamp('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('finance_telegram_updates');
        Schema::dropIfExists('finance_telegram_chats');
    }
}
