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
        //
        Schema::create('emisor', function (Blueprint $table) {
            $table->id();
            $table->string("razon_emisor");
            $table->string("rfc_emisor");
            $table->string("regimen_emisor");
            $table->string("c_postal");
            $table->string("bunit");
            $table->string("email_emisor");
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
        //
        Schema::dropIfExists('emisor');
    }
};
