<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('ni')->unique(); // Mengganti nim dengan ni
            $table->timestamp('ni_verified_at')->nullable(); // Mengganti nim_verified_at dengan ni_verified_at
            $table->string('password');
            $table->enum('roles', ['admin', 'dosen', 'dekan', 'pegawai', 'mahasiswa'])->default('admin'); // Mengganti roles
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}

