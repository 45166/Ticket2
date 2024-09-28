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
        Schema::table('register_tags', function (Blueprint $table) {
            $table->foreign(['building_id'])->references(['id'])->on('buildings')->onUpdate('restrict')->onDelete('set null');
            $table->foreign(['repair_request_id'])->references(['TicketID'])->on('repair_requests')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('register_tags', function (Blueprint $table) {
            $table->dropForeign('register_tags_building_id_foreign');
            $table->dropForeign('register_tags_repair_request_id_foreign');
        });
    }
};
