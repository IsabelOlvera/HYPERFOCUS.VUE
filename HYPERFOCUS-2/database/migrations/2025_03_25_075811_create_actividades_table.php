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
        Schema::create('actividades', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 255);
            $table->longtext('descripcion');
            $table->datetime('fecha_creacion');
            $table->integer('duracion');
            $table->date('fecha_inicio');
            $table->time('hora_inicio');
            $table->date('fecha_fin');
            $table->time('hora_fin');
            $table->tinyinteger('completada');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('pioridad_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('pioridad_id')->references('id')->on('pioridades');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actividades');
    }
};

