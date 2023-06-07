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
        Schema::create('discharge_by_admins', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->date('date');
            $table->integer('service_total_cost');
            $table->string('discharge_dcument_type');
            $table->string('accompanied_on_leaving_by');

            $table->uuid('invoice_id')->nullable();
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->uuid('reciept_id')->nullable();
            $table->foreign('reciept_id')->references('id')->on('reciepts')->onDelete('cascade');
            $table->uuid('discharge_file_id')->nullable();
            $table->foreign('discharge_file_id')->references('id')->on('discharge_files')->onDelete('cascade');
            
            $table->softDeletes($column = 'deleted_at', $precision = 0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discharge_by_admins');
    }
};
