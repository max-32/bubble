<?php

namespace App\Http\Controllers\Auth\Vk;

use Log;
use Input;
use Request;
use Session;
use Response;
use Redirect;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Auth\SocialAuthController\OAuth20Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Collection as Collection;

// Авторизация ВК типа Authorization Code Flow
// https://vk.com/dev/authcode_flow_user

class VkController extends OAuth20Controller
{
    // property

    // static

    // rewrite parent defaults

    protected static $clientSecret = 'xnTNU0UGym7MT200rE0O';
    protected static $clientId = '5753625';
    protected static $authPath = 'https://oauth.vk.com/authorize';
    protected static $accessTokenPath = 'https://oauth.vk.com/access_token';
    protected static $apiPath = 'https://api.vk.com/method/users.get';
    protected static $redirectUri = 'http://undone.com/vk/redirect';
    protected static $responseType = 'code';
    protected static $scope = 'email';
    protected static $apiVersion = '5.60';


    /**
     * Отпарсить полученный access_token в однотипный формат
     * 
     * Пример ответа ВК сервера:
     *
     * {
     *  "access_token":"c4eafbd2f9c150690a40969c180129976835c0d26716dee17902ec965a6f4b67e87677e37013a9c65ab78",
     *  "expires_in":86400,"user_id":172736370,
     *  "email":"max.neverok@gmail.com"
     * }
     * 
     * @param $accessToken accessToken в каком либо виде, полученный от сервера
     * 
     * @return Array
     */
    protected function parseAccessTockenResponseAfterReceiving($response)
    {
        // take the string and return an Array
        return json_decode($response, true);
    }

    /**
     * 
     * @param $collection
     * 
     * @return Array
     */
    protected function prepareUserCollection(Collection $collection) {
        $returnCollection = collect([]);

        $returnCollection->put('id', $collection->get('uid', null));
        $returnCollection->put('email', $collection->get('email', null));
        $returnCollection->put('fname', $collection->get('first_name', null));
        $returnCollection->put('lname', $collection->get('last_name', null));
        $returnCollection->put('sex', $collection->get('sex', null));
        $returnCollection->put('photo', $collection->get('photo_200', null));
        $returnCollection->put('photo_small', $collection->get('photo_50', null));

        return $returnCollection;
    }

    /**
     *  get user via vk api
     *
     *  @return bool(false) | Collection
     */
    public function apiGetUser()
    {
        if ( ! $this->hasAccessTokenOptions()) {
            return null;
        }

        $accessToken = $this->getAccessTokenOptions();

        $params = array
        (
            'uids'         => $accessToken['user_id'],
            'fields'       => 'uid,first_name,last_name,photo_50,photo_200,sex',
            'access_token' => $accessToken['access_token'],
        );
        
        $url = static::$apiPath . '?' . urldecode(http_build_query($params));

        if ($response = $this->curlRequestGet($url))
        {
            $response = collect(json_decode($response, true));

            $collection = ($response->has('response') && count($response['response']) == 1) ?
                    collect($response['response'][0]) :
                    false;

            return $this->prepareUserCollection($collection);
        }

        return false;
    }
}
