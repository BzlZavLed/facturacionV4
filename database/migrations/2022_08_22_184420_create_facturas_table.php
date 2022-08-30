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
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->string("uuid");
            $table->longText("cadenaOriginalSAT");
            $table->longText("fechaTimbrado");
            $table->longText("noCertificadoCFDI");
            $table->longText("noCertificadoSAT");
            $table->longText("qrCode");
            $table->longText("selloCFDI");
            $table->longText("selloSAT");
            $table->longText("cfdi");
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
        Schema::dropIfExists('facturas');
    }
};
