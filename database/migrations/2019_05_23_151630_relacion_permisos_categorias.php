<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RelacionPermisosCategorias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('subCategorias_x_Categorias', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->unsignedInteger('id_cat');
        //     $table->foreign('id_cat')->references('id')->on('Categorias')->onDelete('cascade');
        //     $table->unsignedInteger('id_subcat');
        //     $table->foreign('id_subcat')->references('id')->on('subCategorias')->onDelete('cascade');
        //     $table->timestamps();
        // });

        Schema::create('permisos_x_subCategorias', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_per');
            $table->foreign('id_per')->references('id')->on('permissions')->onDelete('cascade');
            $table->unsignedInteger('id_sub');
            $table->foreign('id_sub')->references('id')->on('subCategorias')->onDelete('cascade');
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
        Schema::dropIfExists('permisos_x_subCategorias');
    }
}
