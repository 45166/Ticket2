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
        Schema::create('register_tags', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('repair_request_id')->index('register_tags_repair_request_id_foreign');
            $table->string('TagNumber', 30)->nullable();
            $table->string('EquipmentNumber');
            $table->string('features');
            $table->string('room');
            $table->string('department');
            $table->timestamps();
            $table->unsignedBigInteger('building_id')->nullable()->index('register_tags_building_id_foreign');
            $table->integer('floor')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('register_tags');
    }
};
