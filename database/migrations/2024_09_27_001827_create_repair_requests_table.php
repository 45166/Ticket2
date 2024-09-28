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
        Schema::create('repair_requests', function (Blueprint $table) {
            $table->bigIncrements('TicketID');
            $table->date('Date');
            $table->string('TagNumber', 30)->nullable();
            $table->string('RepairDetail', 50);
            $table->unsignedBigInteger('Device_ID')->index('fk_device_id');
            $table->char('Tel', 10);
            $table->timestamps();
            $table->unsignedBigInteger('user_id')->nullable()->index('fk_user_id');
            $table->bigInteger('StatusID')->nullable()->index('repair_requests_statusid_foreign');
            $table->string('TicketNumber')->nullable();
            $table->string('note')->nullable();
            $table->boolean('is_evaluated')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair_requests');
    }
};
