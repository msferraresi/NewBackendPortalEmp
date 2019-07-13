<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PermisosXUsuarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permisos_x_usuarios', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_permiso');
            $table->foreign('id_permiso')->references('id')->on('permissions')->onDelete('cascade');
            $table->unsignedInteger('id_usuario');
            $table->foreign('id_usuario')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permisos_x_usuarios');
    }
}
