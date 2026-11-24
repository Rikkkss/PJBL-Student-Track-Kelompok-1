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
    Schema::create('nilais', function (Blueprint $table) {
        $table->id();

        $table->foreignId('mata_kuliah_id')
            ->constrained('mata_kuliah')
            ->cascadeOnDelete();

        $table->string('jenis_penilaian', 100);
        $table->decimal('nilai', 5, 2);
        $table->text('keterangan')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilais');
    }
};
