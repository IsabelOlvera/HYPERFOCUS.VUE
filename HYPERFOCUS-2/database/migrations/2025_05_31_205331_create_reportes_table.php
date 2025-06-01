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
        Schema::create('reportes', function (Blueprint $table) {
        $table->id();
        $table->string('titulo', 255);
        $table->longText('descripcion');
        $table->string('archivo_adjunto', 255)->nullable();
        $table->longText('solucion')->nullable();
        $table->dateTime('fecha_generacion')->nullable();
        $table->dateTime('fecha_solucion')->nullable();

        $table->unsignedBigInteger('estatus_reportes_id');
        $table->foreign('estatus_reportes_id')->references('id')->on('estatus_reportes')->onDelete('cascade');

        $table->unsignedBigInteger('usuario_id');
        $table->foreign('usuario_id')->references('id')->on('users')->onDelete('cascade');

        $table->unsignedBigInteger('asignado_a_id')->nullable();
        $table->foreign('asignado_a_id')->references('id')->on('users')->onDelete('cascade');

        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reportes');
    }
};
