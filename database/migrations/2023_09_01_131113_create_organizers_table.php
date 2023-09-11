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
        Schema::create('organizers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('package_id')->unsigned()->index()->nullable();
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('set null');
            $table->string('username');
            $table->string('name');
            $table->longText('description');
            $table->string('icon');
            $table->string('cover');
            $table->string('type');

            $table->string('contact_phone')->nullable();
            $table->string('contact_email');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizers');
    }
};
