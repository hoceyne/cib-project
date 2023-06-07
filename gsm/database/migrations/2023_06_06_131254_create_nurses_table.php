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
        Schema::create('nurses', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('national_id');
            $table->string('nationality');
            $table->string('blood_group');
            $table->string('phone_number');
            $table->string('gender');
            $table->date('birth_date');
            $table->string('birth_place');
            $table->string('family_situation');

            $table->uuid('service_id')->nullable();
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');

            $table->softDeletes($column = 'deleted_at', $precision = 0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nurses');
    }
};
