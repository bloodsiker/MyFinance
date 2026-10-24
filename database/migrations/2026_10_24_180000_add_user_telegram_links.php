<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserTelegramLinks extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('telegram_id', 20)->nullable()->unique();
            $table->timestamp('telegram_link_managed_at')->nullable();
        });
        Schema::create('finance_telegram_links', function (Blueprint $table) {
            $table->string('token_hash', 64)->primary();
            $table->unsignedBigInteger('user_id');
            $table->timestamp('expires_at');
            $table->timestamp('used_at')->nullable();
            $table->string('claimed_by', 20)->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('finance_telegram_links');
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['telegram_id']);
            $table->dropColumn(['telegram_id', 'telegram_link_managed_at']);
        });
    }
}
