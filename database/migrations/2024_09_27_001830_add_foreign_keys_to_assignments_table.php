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
        Schema::table('assignments', function (Blueprint $table) {
            $table->foreign(['TicketID'], 'assignments_ibfk_1')->references(['TicketID'])->on('repair_requests')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['user_id'], 'assignments_ibfk_2')->references(['id'])->on('users')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropForeign('assignments_ibfk_1');
            $table->dropForeign('assignments_ibfk_2');
        });
    }
};
