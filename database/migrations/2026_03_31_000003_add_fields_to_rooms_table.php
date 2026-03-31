<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->string('room_number')->nullable()->unique();
            $table->integer('floor')->default(1);
            $table->string('bed_type')->default('double');
            $table->integer('room_size')->nullable();
            $table->json('amenities')->nullable();
            $table->string('image')->nullable();
            $table->integer('max_occupancy')->default(2);
        });
    }

    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn([
                'room_number',
                'floor',
                'bed_type',
                'room_size',
                'amenities',
                'image',
                'max_occupancy',
            ]);
        });
    }
};
