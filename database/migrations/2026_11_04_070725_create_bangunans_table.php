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
        Schema::create('bangunans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('kawasan_id')
                ->constrained('kawasans')
                ->cascadeOnDelete();

            $table->string('nama');

            $table->text('deskripsi')->nullable();

            // Posisi pin berdasarkan gambar kawasan
            $table->decimal('position_x', 8, 4)->nullable();
            $table->decimal('position_y', 8, 4)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bangunans');
    }
};
