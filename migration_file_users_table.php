<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('new_phone_number')->nullable();
            $table->string('phone_number_confirmation_code')->nullable();
            $table->string('email')->nullable();
            $table->string('new_email')->nullable();
            $table->string('email_confirmation_code')->nullable();
            $table->string('telegram')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('phone_number');
            $table->string('new_phone_number')->nullable();
            $table->string('phone_number_confirmation_code')->nullable();
            $table->dropColumn('email');
            $table->string('new_email')->nullable();
            $table->string('email_confirmation_code')->nullable();
            $table->dropColumn('telegram');
            $table->timestamps();
        });
    }
}
