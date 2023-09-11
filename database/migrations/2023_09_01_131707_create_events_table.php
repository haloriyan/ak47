<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('organizer_id')->unsigned()->index();
            $table->foreign('organizer_id')->references('id')->on('organizers')->onDelete('cascade');
            $table->string('title');
            $table->string('category')->nullable();
            $table->string('topic')->nullable();
            $table->longText('description');
            $table->string('cover');

            $table->dateTime('start_date');
            $table->dateTime('end_date');
            
            $table->text('address');
            $table->string('coordinate')->nullable();
            $table->string('province');
            $table->string('city');
            $table->integer('province_id')->nullable();
            $table->integer('city_id')->nullable();
            $table->integer('district_id')->nullable();

            $table->integer('max_buy_ticket');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
