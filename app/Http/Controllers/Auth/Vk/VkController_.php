<?php

namespace App\Http\Controllers\Auth\Vk;

use Log;
use Input;
use Request;
use Session;
use Response;
use Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

// Авторизация ВК типа Authorization Code Flow
// https://vk.com/dev/authcode_flow_user

class VkController extends Controller
{
    // const

    // Защищенный ключ Вашего приложения (указан в настройках приложения)
    const VK_CLIENT_SECRET = 'xnTNU0UGym7MT200rE0O';
    // Идентификатор приложения ВК
    const VK_CLIENT_ID = '5753625';
    // Адрес авторизации (куда кидать юзера с нашего сайта на сайт ВК)
    const VK_AUTH_PATH = 'https://oauth.vk.com/authorize';
    // Адрес получения access token (куда обратиться за access_token)
    const VK_ACCESS_TOKEN_PATH = 'https://oauth.vk.com/access_token';
    // Адрес, на который будет передан code, после (подтверждения/не подтверждения) юзером запрошенных полномочий
    const VK_REDIRECT_URI = 'http://undone.com/vk/redirect';
    // Тип ответа ВК сервера (Тип ответа, который Вы хотите получить. Укажите code)
    const VK_RESPONSE_TYPE = 'code';
    // VK scope (если хотим получить Email). Email может быть так же запрещен юзером, надо учитывать
    const VK_SCOPE = 'email';
    // VK api version
    const VK_API_VERSION = '5.60';

    // property

    // Временный код от сервера ВК. Получен при обращении по адресу -> generateAuthLink()
    protected $vkTempCode = null;
    // Опции access_token. При получении данных это поле соответственно обновляется
    protected $accessTokenOptions = null;



    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    // methods

    // static

    /**
     * Сгенерировать адрес для перенаправления юзера на ВК-сервер для получения полномочий
     *
     * @return String
     */
    public static function generateAuthLink()
    {
        $params = array
        (
            'response_type' => self::VK_RESPONSE_TYPE,
            'client_id' => self::VK_CLIENT_ID,
            'redirect_uri' => self::VK_REDIRECT_URI,
            'scope' => self::VK_SCOPE,
            'v' => self::VK_API_VERSION,
        );
    
        return (string) self::VK_AUTH_PATH . '?' . urldecode(http_build_query($params));
    }


    // dynamic

    /**
     * Получить код от сервера ВК.
     *
     * Примерный ответ сервера ВК после подтверждения прав юзера:
     * REDIRECT_URI?code=7a6fa4dff77a228eeda56603b8f53806c883f011c40b72630bb50df056f6479e52a 
     * 
     * В случае возникновения ошибки браузер пользователя будет перенаправлен с кодом и описанием ошибки:
     * REDIRECT_URI?error=invalid_request&error_description=Invalid+display+parameter 
     * 
     * @return void
     */
    public function receiveRedirectWithCode(Input $input)
    {
        // # 1
        // check if error ...
        if ( null !== ($input->get('error', null)) ) {
            // Ошибка получения временного кода доступа ВК (возможно юзер запретил досступ)
            // Обработать ошибку получения временного кода
            $this->handleReceiveCodeError(Input::all());

            return Redirect::to('/welcome')->withErrors(['message' => 'Не удалось авторизоваться.']);
        }

        // # 2
        // check if code not exists ...
        if ( null === ($code = $input->get('code', null)) ) {
            // Ошибка получения временного кода доступа ВК
            Log::info('Ошибка при получении "code" от ВК. Не получен GET параметр "code"', (array) Input::all());
          
            return Redirect::to('/welcome')->withErrors(['message' => 'Не удалось авторизоваться. Сообщите администратору.']);
        }

        // perform action ...

        // update code
        $this->vkTempCode = $code;


        // Получение access_token
        if ( ! ($accessToken = $this->getAccessToken( $this->vkTempCode )) ) {
            $this->handleReceiveAccessTokenError(Input::all());

            return Redirect::to('/welcome')->withErrors(['message' => 'Не удалось авторизоваться']);
        }

        // Получение access_token
        if ( isset($accessToken['error']) ) {
            $this->handleReceiveAccessTokenError($accessToken);

            return Redirect::to('/welcome')->withErrors(['message' => 'Не удалось авторизоваться']);
        }

        // access_token получен.
        // Объект: {"access_token":"token_key_here", "expires_in":43200, '''user_id":11111}
        // Далее можно регать или логинить юзера

        // Обработать получение access_token
        // Операция прошла успешно. Можно выполнить необходимый редирект или иное действие
        $this->handleReceiveAccessToken( $accessToken );

        return Redirect::to('/welcome');
    }

    /**
     * Обработать получение access_token
     * 
     * @return void
     */
    protected function handleReceiveAccessToken($accessToken)
    {
        Log::info('Авторизация через Vk прошла успешно.', $accessToken->toArray());

        Session::flash('message', 'Вы успешно авторизовались!');
    }

    /**
     * Получение access_token
     * 
     * Для получения access_token необходимо выполнить запрос с Вашего сервера на https://oauth.vk.com/access_token
     *
     * https://oauth.vk.com/access_token?
     * client_id=1&client_secret=H2Pk8htyFD8024mZaPHm&
     * redirect_uri=http://mysite.ru&code=7a6fa4dff77a228eeda56603b8f53806c883f011c40b72630bb50df056f6479e52a
     * 
     * Примерный ответ сервера:
     * {"access_token":"533bacf01e11f55b536a565b57531ac114461ae8736d6506a3", "expires_in":43200, "user_id":66748}
     * 
     * При ошибке:
     * {"error":"invalid_grant","error_description":"Code is expired."}
     * 
     * @param $code Временный код, полученный от ВК ранее
     * 
     * @return Void | Bool
     */
    public function getAccessToken($code)
    {
        // Кода нет. Действие невозможно
        if ( ! is_string($code)) {
            return false;
        }

        // Адрес с параметрами, по которому нужно обратиться для получения access_token
        $path = $this->generateAccessTokenPath( $code );
        // Обращение по адресу $path
        $response = $this->curlRequest( $path );
        $response = collect(json_decode($response, true));

        // update access_token
        $this->accessTokenOptions = $response;

        // access_token получен успешно
        return $response;
    }

    /**
     * Сгенерировать строку (адрес) для получения access_token
     * 
     * Для получения access_token необходимо выполнить запрос с Вашего сервера на https://oauth.vk.com/access_token
     *
     * https://oauth.vk.com/access_token?
     * client_id=1&client_secret=H2Pk8htyFD8024mZaPHm&
     * redirect_uri=http://mysite.ru&code=7a6fa4dff77a228eeda56603b8f53806c883f011c40b72630bb50df056f6479e52a
     * 
     * @param $temporaryVkCode временный код, полученный с сервера ВК
     * 
     * @return String
     */
    protected function generateAccessTokenPath($temporaryVkCode)
    {
        $params = array
        (
            'code'              => $temporaryVkCode,
            'client_id'         => self::VK_CLIENT_ID,
            'client_secret'     => self::VK_CLIENT_SECRET,
            'redirect_uri'      => self::VK_REDIRECT_URI,
        );
    
        return (string) self::VK_ACCESS_TOKEN_PATH . '?' . urldecode(http_build_query($params));
    }

    /**
     *  Make request to given url via curl
     *  
     *  @return mixed
     */
    private function curlRequest($url)
    {
        $curl_handle=curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $url);
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_USERAGENT, Request::header('user-agent'));
        $response = curl_exec($curl_handle);
        curl_close($curl_handle);
        return $response;
    }

    /**
     * Обработать ошибку получения access_token
     *
     * @param $input Доп. параметры в виде массива, поясняющие ошибку
     *
     * @return Void
     */
    protected function handleReceiveAccessTokenError($input)
    {
        Log::info('Ошибка при получении "access_token" от ВК', (array) $input);
    }

    /**
     * Обработать ошибку получения временного кода ВК
     *
     * @param $errorsGET Входные данные (параметры GET) от сервера ВК
     *
     * @return Void
     */
    protected function handleReceiveCodeError($errorsGET)
    {
        Log::info('Ошибка при получении "code" от ВК', (array) $errorsGET);
    }





    /**
     * Получен ли access_token
     *
     * @return Bool
     */
    public function hasAccessTokenOptions()
    {
        return $this->accessTokenOptions !== null;
    }

    /**
     * Получить объект access_token
     *
     * @return Object | null
     */
    public function getAccessTokenOptions()
    {
        if ( ! $this->hasAccessTokenOptions()) {
            return null;
        }
        return $this->accessTokenOptions !== null;
    }

    /**
     * Получен ли код от сервера ВК для возможности получения access_token
     *
     * @return Bool
     */
    public function hasTempCode()
    {
        return $this->vkTempCode !== null;
    }

    /**
     * Получить значение vkTempCode
     *
     * @return String | null
     */
    public function getTempCode()
    {
        if ($this->hasTempCode()) {
            return (string) $this->vkTempCode;
        }
        return null;
    }
}
