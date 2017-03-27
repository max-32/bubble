<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_info', function (Blueprint $table) {
            $table->increments('user_id');
            $table->string('fname')->nullable();
            $table->string('lname')->nullable();
            $table->string('mname')->nullable();
            $table->string('sex')->nullable();
            $table->date('dob')->nullable();
            $table->string('photo')->nullable();
            $table->string('phone')->nullable();
            $table->string('about')->nullable();
            $table->string('photo_height')->nullable();
            $table->string('photo_width')->nullable();
            $table->string('photo_small')->nullable();
            $table->timestamp('registration_date')->nullable()->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->timestamps();

            // add fields...

            // foreign key
            $table->index('sex');

            // users
            $table->foreign('user_id')
                ->references('id')->on('users')
                // если из `users` кто то удален - грохаем его и тут
                ->onUpdate('cascade')
                ->onDelete('cascade');

            // users_sex
            $table->foreign('sex')
                ->references('id')->on('users_sex')
                // если из `users_sex` кто то удален - тут просто обнуляем поле
                ->onUpdate('set null')
                ->onDelete('set null');
        });

        DB::statement("ALTER TABLE `users_info` comment 'Полная информация по пользователям'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users_info');
    }
}
