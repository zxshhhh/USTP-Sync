<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            // Create an auto-incrementing primary key
            $table->id();
            // Create a string column for the user's password, with a max length of 20 characters
            $table->string(column: 'password', length: 20)->nullable(value: false);
            // Create a string column for the user's username, with a max length of 20 characters
            $table->string(column: 'username', length: 20);
            $table->timestamps(); // created_at and updated_at timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}