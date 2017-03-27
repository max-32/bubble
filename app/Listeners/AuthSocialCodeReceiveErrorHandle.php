<?php

namespace App\Listeners;

use Log;
use Input;
use Session;
use App\Events\AuthSocialCodeReceiveError;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AuthSocialCodeReceiveErrorHandle
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AuthSocialCodeReceiveError  $event
     * @return void
     */
    public function handle(AuthSocialCodeReceiveError $event)
    {
        // Записать в сессию сообщение
        Session::flash('error', 'Не удалось авторизоваться.');

        // Залогировать ошибку
        Log::info('Ошибка при получении "code" :: ' . get_class($event->auth20Class), Input::all());
    }
}
