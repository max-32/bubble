<?php

namespace App\Listeners;

use DB;
use Log;
use Auth;
use Input;
use Session;
use App\User;
use App\UserInfo;
use Redirect;
use App\SocialAuthUser;
use App\SocialAuthClient;
use App\Events\AuthSocialAccessTokenReceived;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AuthSocialAccessTokenReceivedHandle
{
    /**
     * Название соц. сети (vk, google). Генериреутся из имени класса динамически
     *
     * @var String
     */
    private $socialAuthClientName = null;

    /**
     * Данные пользователя от сервера соц. сети
     *
     * @var Collection
     */
    private $collectionUser = null;


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
     * @param  AuthSocialAccessTokenReceived  $event
     * @return void
     */
    public function handle(AuthSocialAccessTokenReceived $event)
    {
        // #. На этом этапе access_token точно получен. Теперь нужно авторизовать пользователя
        
        // 1. Проверить, зареган ли юзер на сайте -> залогинить -> редирект

        // 2. Если не залогинен - зарегать -> залогинить -> редирект

        // #. google: 107369481039788974288
        // #. vk: 172736370

        // Данные пользователя от сервера соц. сети
        $this->collectionUser = $event->authController->apiGetUser();
        // Название соц. сети (vk, google). Генериреутся из имени класса динамически
        $this->socialAuthClientName = $event->authController->getSocialAuthClientName();


        // Не удалось получить данные пользователя из-за
        // Какой то неприятности (их может быть ооочень много)
        // При всем этом Токен может иметься в наличии  и быть валидным
        // Интерпретируем данную ситуацию как "Непредвиденная ошибка"
        if ( ! $this->collectionUser ) {
            $this->usersInformationUnavailable($event->authController); return;
        }

        // Необходимо сменить название класса или усложнить код метода ::getSocialAuthClientName()
        if ( ! $this->socialAuthClientName ) {
            $this->socialAuthClientNameUnresolved($event->authController); return;
        }


        // Поиск юзера в БД (по ID его соц. сети и типу соц. сети)
        $databaseSocialAuthUser =
            (SocialAuthUser
                ::join('social_auth_clients', 'social_auth_type', '=', 'client_id')
                ->where('social_auth_user_id', $this->collectionUser['id'])
                ->where('client_name', '=', $this->socialAuthClientName)
                ->get())
            ->first();
            
        if ($databaseSocialAuthUser instanceof SocialAuthUser) {
            // Exists already. Login.

            $succeed = $this->doLoginById( $databaseSocialAuthUser['user_id'] );
        }
        else {
            // Do not xists. Create and Login.

            // Регистрация только транзакцией ибо задействуется 3 таблицы и все должны быть созданы.
            // Может получиться ситуация, что в users пользователь есть, а в social_auth_registered нету.
            DB::transaction(function() {
                try
                {
                    $userRegisteredId = $this->doRegister( $this->collectionUser, $this->socialAuthClientName );
                }
                catch (Exception $e)
                {
                    // Log the data, stop script
                    $this->handleException($e); throw $e;
                }

                // Залогинить
                $succeed = $this->doLoginById( $userRegisteredId );
            });
        }
    }

    /**
     * Залогинить пользователя
     *
     * @param Int $userRegisteredId - ID пользователя
     *
     * @return Bool
     */
    private function doLoginById($userRegisteredId)
    {
        $credentials = [
            'id' => $userRegisteredId,      # ID юзера из `users`
            'password' => 'social_auth'     # одинаковый для всех
        ];

        $succeed = Auth::loginUsingId($userRegisteredId, true);
        
        ###
        ### RISE AN EVENT OnUserLogin, OnUserLogout ...
        ###

        if ($succeed) {
            Log::debug('User with ID [' . $userRegisteredId . '] logged ['. $this->socialAuthClientName .'].', []);
            return true;
        } else {
            Log::debug('User with ID [' . $userRegisteredId . '] NOT logged ['. $this->socialAuthClientName .'].', []);
            return false;
        }
    }

    /**
     * Зарегисстрировать пользователя
     *
     * @param Collection $collectionUser - Информация пользователя от соц. сети 
     * @param String $socialAuthClientName - Название соц. сети
     *
     * @throws Exception
     *
     * @return void
     */
    private function doRegister($collectionUser, $socialAuthClientName)
    {
        // ID зарегистрированного пользователя
        $userRegisteredId = false;

        // Создать запись в основной таблице `users`
        $user = new User;
        // $user->name = $collectionUser['fname'] ?? null;
        $user->email = $collectionUser['email'] ?? null;
        $user->password = 'social_auth';    # always the same
        
        try {
            $user->save();
            $userRegisteredId = $user->id;
        }
        catch(Exception $e)
        {
            $this->handleException($e); throw $e;
        }

        // just simple processing here
        list($width, $height) = @getimagesize( $collectionUser['photo'] );

        // Создать запись в дополнительной таблице `users_info`
        $userInfo = new UserInfo;
        $userInfo->user_id = $userRegisteredId;
        $userInfo->fname = $collectionUser['fname'] ?? null;
        $userInfo->lname = $collectionUser['lname'] ?? null;
        $userInfo->photo = $collectionUser['photo'] ?? null;
        $userInfo->photo_width = $width;
        $userInfo->photo_small = $collectionUser['photo_small'] ?? null;
        $userInfo->photo_height = $height;

        if ($width < 110 or $height < 110) {
            $userInfo->photo = url('img/default-profile.jpg');
            $userInfo->photo_small = null;
        }

        try {
            $userInfo->save();
        }
        catch(Exception $e)
        {
            $this->handleException($e); throw $e;
        }

        // Поиск ID для используемой соц. сети ['vk' => 1, 'google' => 2, ...]
        $databaseSocialAuthClient = SocialAuthClient
            ::where('client_name', '=', $socialAuthClientName)
            ->first();

        $userSocialAuth = new SocialAuthUser;
        $userSocialAuth->user_id = $userRegisteredId;
        $userSocialAuth->social_auth_user_id = $collectionUser['id'];
        $userSocialAuth->social_auth_type = $databaseSocialAuthClient->client_id;

        try {
            $userSocialAuth->save();
        }
        catch(Exception $e)
        {
            $this->handleException($e); throw $e;
        }

        return $userRegisteredId;
    }


    /**
     * Залогировать ошибку
     *
     * @return void
     */
    private function handleException(Exception $e)
    {
        Log::warning(
            'Ошибка при попытке регистрации пользователя через соц. сеть', [
                'user_array' => (array) $this->collectionUser,
                'social_net' => $this->socialAuthClientName,
                'exception' => $e->getMessage(),
        ]);
    }

    /**
     * Залогировать ошибку
     *
     * @return void
     */
    private function usersInformationUnavailable($authController)
    {
        // Записать в сессию сообщение
        Session::flash('error', 'Не удалось авторизоваться из-за неизвестной неисправности.');

        // Залогировать ошибку
        Log::notice(
            'Ошибка при авторизации через соц. сеть (данные юзера не получены от сервера) :: ' . get_class($authController), [
                'tmp_code' => $authController->getTempCode(),
                'access_token' => $authController->getAccessTokenOptions(),
        ]);
    }

    /**
     * Залогировать ошибку
     *
     * @return void
     */
    private function socialAuthClientNameUnresolved($authController)
    {
        // Необходимо сменить название класса или усложнить код метода ::getSocialAuthClientName()
        Log::notice('Ошибка при генерировании названия соц. сети [getSocialAuthClientName()]', [get_class($authController)]);
    }
}
