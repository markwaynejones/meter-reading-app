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
        Schema::create('meters', function (Blueprint $table) {
            $table->id();
            $table->string('mpxn')->unique();
            // making serial_number nullable because no frontend logic required for it as part of this test
            $table->string('serial_number')->nullable();
            $table->enum('type', ['gas', 'electric']);
            $table->date('installation_date');
            $table->integer('estimated_annual_consumption')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meters');
    }
};
