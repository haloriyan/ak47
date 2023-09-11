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
        Schema::create('organizer_memberships', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('organizer_id')->unsigned()->index()->nullable();
            $table->foreign('organizer_id')->references('id')->on('organizers')->onDelete('set null');
            $table->bigInteger('package_id')->unsigned()->index()->nullable();
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('set null');
            $table->dateTime('expiration');
            $table->string('payment_reference');
            $table->bigInteger('payment_amount');
            $table->string('payment_url')->nullable();
            $table->string('payment_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizer_memberships');
    }
};
