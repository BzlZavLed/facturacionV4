<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('concepto_internos', function (Blueprint $table) {
            $table->id();
            $table->string('claveProductoServicio');
            $table->string('descripcionConcepto');
            $table->string('cuentasContables');
            $table->string('claveUnidadFacturacion');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('concepto_internos');
    }
};
