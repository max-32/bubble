<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocialAuthClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_auth_clients', function (Blueprint $table) {
            $table->integer('client_id')->unique()->comment = 'ID клиента';
            $table->string('client_name')->comment = 'Имя (описание) клиента';
            $table->nullableTimestamps();

            // foreign key
            $table->index('client_id');
        });

        DB::statement("ALTER TABLE `social_auth_clients` comment 'Авторизация через соц. сети: список доступных клиентов'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('social_auth_clients');
    }
}
