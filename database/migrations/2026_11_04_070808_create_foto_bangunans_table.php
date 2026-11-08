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
        Schema::create('foto_bangunans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('bangunan_id')
                ->constrained('bangunans')
                ->cascadeOnDelete();

            $table->string('foto');

            $table->string('judul')->nullable();

            $table->text('keterangan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foto_bangunans');
    }
};
