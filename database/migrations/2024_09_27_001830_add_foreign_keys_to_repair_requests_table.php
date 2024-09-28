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
        Schema::table('repair_requests', function (Blueprint $table) {
            $table->foreign(['Device_ID'], 'fk_device_id')->references(['DeviceID'])->on('devices')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['user_id'], 'fk_user_id')->references(['id'])->on('users')->onUpdate('restrict')->onDelete('set null');
            $table->foreign(['StatusID'])->references(['StatusID'])->on('statuses')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('repair_requests', function (Blueprint $table) {
            $table->dropForeign('fk_device_id');
            $table->dropForeign('fk_user_id');
            $table->dropForeign('repair_requests_statusid_foreign');
        });
    }
};
