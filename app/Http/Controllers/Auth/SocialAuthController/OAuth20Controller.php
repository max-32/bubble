<?php

namespace App\Http\Controllers\Auth\SocialAuthController;

use Log;
use Input;
use Event;
use Request;
use Session;
use Response;
use Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection as Collection;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

abstract class OAuth20Controller extends Controller
{
    // property

    // static

    // Защищенный ключ Вашего приложения (указан в настройках приложения)
    protected static $clientSecret = null;
    // Идентификатор приложения
    protected static $clientId = null;
    // Адрес авторизации (куда кидать юзера с нашего сайта на сайт)
    protected static $authPath = null;
    // Адрес получения access token (куда обратиться за access_token)
    protected static $accessTokenPath = null;
    // Адрес получения данных о пользователе
    protected static $apiPath = null;
    // Адрес, на который будет передан code, после (подтверждения/не подтверждения) юзером запрошенных полномочий
    protected static $redirectUri = null;
    // Тип ответа сервера (Тип ответа, который Вы хотите получить. Укажите code)
    protected static $responseType = null;
    // scope (если хотим получить Email). Email может быть так же запрещен юзером, надо учитывать
    protected static $scope = null;
    // api version
    protected static $apiVersion = null;

    // dynamic

    // Временный код от сервера. Получен при обращении по адресу -> generateAuthLink()
    private $tempCode = null;
    // Опции access_token. При получении данных это поле соответственно обновляется
    private $accessTokenOptions = null;
    // Имя соц. сети. Генерируется из названия класса динамически
    private $socialAuthClientName = null;



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

    // abstract

    /**
     *  Получить инфу о юзере от соц. сети
     *
     *  @return bool(false) | Collection
     */
    abstract public function apiGetUser();

    /**
     * Подготовить данные о пользователе
     *
     * @param $collection - Данные пользователя от сервера. Их надо преобразовать в один стандартный вид.
     *
     * @return Collection
     */
    abstract protected function prepareUserCollection(Collection $collection);

    /**
     * Ответ (успешный или неуспешный) получен в каком то (каждый дрочит как хочет) виде
     * От авторизованного сервера (ВК, Facebook)
     * Его надо преобразовать в массив.
     * Каждый наследник обязан это делать перегрузкой метода parseAccessTockenResponseAfterReceiving
     *
     * @param $response - Ответ от авторизованного сервера (ВК, Facebook) полученный через Curl
     *
     * @return Collection
     */
    abstract protected function parseAccessTockenResponseAfterReceiving($response);


    // static

    /**
     * Сгенерировать адрес для перенаправления юзера на сервер для получения полномочий
     *
     * @return String
     */
    public static function generateAuthLink()
    {
        $params = array
        (
            'response_type' => static::$responseType,
            'redirect_uri' => static::$redirectUri,
            'client_id' => static::$clientId,
            'scope' => static::$scope,
            'v' => static::$apiVersion,
        );
    
        return (string) static::$authPath . '?' . urldecode(http_build_query($params));
    }


    // dynamic

    /**
     * Получить код от сервера
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
        // check if error OR check if code not exists ...
        if (( null !== ($input->get('error', null)) )
        || (  null === ($code = $input->get('code', null)) ))
        {
            // Ошибка получения временного кода доступа (возможно юзер запретил доступ)
            Event::fire( new \App\Events\AuthSocialCodeReceiveError($this) );
            
            return Redirect::back();
        }

        // perform action ...

        // code получен.
        // Далее можно с его помощью получать access_token
        $this->handleReceiveTempCode($code);


        // Получение access_token
        if (( ! ($accessToken = $this->getAccessToken( $this->getTempCode() )))
             || $accessToken->isEmpty()
             || $accessToken->has('error'))
        {
            // access_token не удалоссь получить
            // Либо пустой ответ, либо ответ, сообщающий ошибку
            Event::fire( new \App\Events\AuthSocialAccessTokenReceiveError($this) );

            return Redirect::back();
        }


        // access_token получен
        $this->handleReceiveAccessTokenResponse($accessToken);


        // access_token получен
        // access_token имеет примерно следующий вид:
        // {"access_token":"token_key_here", "expires_in":43200, 'user_id":11111}
        // Далее можно регать или логинить юзера

        // Обработать получение access_token
        Event::fire( new \App\Events\AuthSocialAccessTokenReceived($this) );

        return Redirect::to('/');
    }

    /**
     * Получение access_token
     * 
     * Для получения access_token необходимо выполнить запрос с Вашего сервера на авторизованный сервер
     *
     * Пример на ВК:
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
     * @return Collection
     */
    public function getAccessToken($code)
    {
        // Кода нет. Действие невозможно
        if ( ! is_string($code)) {
            return false;
        }

        // Адрес с параметрами, по которому нужно обратиться для получения access_token
        $accessTokenPath = static::$accessTokenPath;
        $accessTokenParams = $this->getAccessTokenRequestParams($code);

        // Обращение по адресу $path
        $response = $this->curlRequestPost($accessTokenPath, $accessTokenParams);

        // Ответ (успешный или неуспешный) получен в каком то (каждый дрочит как хочет) виде
        // От авторизованного сервера (ВК, Facebook)
        // Его надо преобразовать в массив.
        // Каждый наследник обязан это делать перегрузкой метода parseAccessTockenResponseAfterReceiving
        $response = $this->parseAccessTockenResponseAfterReceiving($response);

        // Полученный ответ преобразуем к Laravel коллекцию
        return collect($response);
    }

    /**
     * Сгенерировать массив с параметрами, необходимыми для запроса access_token
     * 
     * Для получения access_token необходимо выполнить запрос с Вашего сервера на сервер авторизации
     *
     * Пример:
     * https://oauth.vk.com/access_token?
     * client_id=1&client_secret=H2Pk8htyFD8024mZaPHm&
     * redirect_uri=http://mysite.ru&code=7a6fa4dff77a228eeda56603b8f53806c883f011c40b72630bb50df056f6479e52a
     * 
     * @param $temporaryCode временный код, полученный с сервера
     * 
     * @return Array
     */
    protected function getAccessTokenRequestParams($temporaryCode)
    {
        return array
        (
            'code'              => $temporaryCode,
            'client_id'         => static::$clientId,
            'client_secret'     => static::$clientSecret,
            'redirect_uri'      => static::$redirectUri,
            'grant_type'        => 'authorization_code',
        );
    }

    /**
     *  Make request to given url via curl [GET method]
     *  
     *  @return mixed
     */
    protected function curlRequestGet($url)
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
     *  Make request to given url via curl [POST method]
     *  
     *  @return mixed
     */
    protected function curlRequestPost($url, $paramsPost)
    {
        $curl_handle=curl_init();
        curl_setopt($curl_handle, CURLOPT_POST, 1);
        curl_setopt($curl_handle, CURLOPT_URL, $url);
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, urldecode(http_build_query($paramsPost)));
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_HTTPHEADER, ['Content-type: application/x-www-form-urlencoded']);
        curl_setopt($curl_handle, CURLOPT_USERAGENT, Request::header('user-agent'));
        $response = curl_exec($curl_handle);
        curl_close($curl_handle);
        return $response;
    }


    /**
     * Получить имя соц. сети через название класса контроллера (VkController -> "vk")
     *
     * @return String|false
     */
    public function getSocialAuthClientName()
    {
        if ($this->socialAuthClientName === null)
        {
            $re = '/(\w+)Controller/';
            $str = (new \ReflectionClass($this))->getShortName();

            preg_match_all($re, $str, $matches);

            if (isset($matches[1]) and isset($matches[1][0]) and count($matches[1][0]) == 1) {
                $this->socialAuthClientName = strtolower($matches[1][0]);
            } else {
                $this->socialAuthClientName = false;
            }
        }

        return $this->socialAuthClientName;
    }

    /**
     * Обработать получение access_token
     * 
     * @param Collection|Array $accessToken - Объект access_token
     * 
     * @return void
     */
    protected function handleReceiveAccessTokenResponse($accessToken)
    {
        if ($accessToken instanceof Collection) {
            $this->accessTokenOptions = $accessToken;
        }
    elseif (is_array($accessToken)) {
            $this->accessTokenOptions = collect($accessToken);
        }
        else {
            throw new \UnexpectedValueException('Access_Token must be instance of Laravel Collection class.');
        }
    }

    /**
     * Обработать получение CODE
     * 
     * @param string $code - код, полученный от сервера авторизации
     * 
     * @return Void
     */
    protected function handleReceiveTempCode($code)
    {
        if (is_string($code)) {
            $this->tempCode = $code;
        }
        else {
            throw new \UnexpectedValueException('Code must be a string [Social Auth].');
        }
    }

    /**
     * Получен ли access_token
     *
     * @return Bool
     */
    public function hasAccessTokenOptions()
    {
        return ($this->accessTokenOptions !== null) && ( ! $this->accessTokenOptions->isEmpty());
    }

    /**
     * Получить объект access_token
     *
     * @return Object (Collection) | null
     */
    public function getAccessTokenOptions()
    {
        if ( ! $this->hasAccessTokenOptions()) {
            return null;
        }
        return $this->accessTokenOptions;
    }

    /**
     * Получен ли код от сервера ВК для возможности получения access_token
     *
     * @return Bool
     */
    public function hasTempCode()
    {
        return $this->tempCode !== null && is_string($this->tempCode);
    }

    /**
     * Получить значение tempCode
     *
     * @return String | null
     */
    public function getTempCode()
    {
        if ($this->hasTempCode()) {
            return $this->tempCode;
        }

        return null;
    }
}
