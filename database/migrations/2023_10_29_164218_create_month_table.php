<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonthTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('months', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('year_id');
            $table->string('name');
            $table->string('number');
            $table->timestamps();

            $table->foreign('year_id')->references('id')->on('years')->onDelete('cascade');
        });

        Schema::create('month_category', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('month_id');
            $table->unsignedBigInteger('category_id');
            $table->double('budget')->default(0);

            $table->unique(['month_id', 'category_id']);

            $table->foreign('month_id')->references('id')->on('months')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('month_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('user_id');
            $table->string('title');
            $table->string('type', 10);
            $table->double('amount')->default(0);
            $table->timestamps();

            $table->foreign('month_id')->references('id')->on('months')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('month_category');
        Schema::dropIfExists('month');
    }
}
