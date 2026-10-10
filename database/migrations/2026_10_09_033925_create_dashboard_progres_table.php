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
        Schema::create('dashboard_progres', function (Blueprint $table) {
            $table->id();
            $table->string('area');
            $table->string('nama_dokumen');
            $table->string('spreadsheet_id');
            $table->text('spreadsheet_url');
            $table->string('sheet_range')->default('Sheet1!A1:Z1000');
            $table->unsignedTinyInteger('progres')->default(5);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */

    public function down(): void
    {
        Schema::dropIfExists('dashboard_progres');
    }
};
