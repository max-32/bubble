<?php

namespace App\Listeners;

use Log;
use Input;
use Session;
use App\Events\AuthSocialAccessTokenReceiveError;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AuthSocialAccessTokenReceiveErrorHandle
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
     * @param  AuthSocialAccessTokenReceiveError  $event
     * @return void
     */
    public function handle(AuthSocialAccessTokenReceiveError $event)
    {
        // Записать в сессию сообщение
        Session::flash('error', 'Не удалось авторизоваться.');

        // Залогировать ошибку
        Log::info(
            'Ошибка при получении "access_token" :: ' . get_class($event->auth20Class),
            (array) $event->auth20Class->getAccessTokenOptions()
        );
    }
}
