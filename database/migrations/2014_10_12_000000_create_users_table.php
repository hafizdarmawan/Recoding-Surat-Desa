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
            $table->string('photo')->default('assets/images/users/user-1.jpg');
            $table->string('sin')->unique();
            $table->string('name');
            $table->string('birth_place')->nullable();
            $table->date('birt_date')->nullable();
            $table->enum('gender', ['Laki-Laki', 'Perempuan']);
            $table->text('address')->nullable();
            $table->enum('religion', ['Islam', 'Protestan', 'Katolik', 'Hindu', 'Buddha', 'Konghucu']);
            $table->enum('marital_status', ['Belum Kawin', 'Kawin', 'Cerai']);
            $table->string('phone_number')->nullable();
            $table->string('professions')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
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
