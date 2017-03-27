<?php

namespace App\Http\Controllers\Auth\Facebook;

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

// Авторизация Facebook типа Authorization Code Flow
// https://developers.facebook.com/apps/1807327909544625/dashboard/

class FacebookController extends OAuth20Controller
{
    // property

    // static

    // rewrite parent defaults
    
    protected static $clientSecret = 'de0f73d38eeea69a64633708da8a2909';
    protected static $clientId = '1807327909544625';
    protected static $authPath = 'https://www.facebook.com/dialog/oauth';
    protected static $accessTokenPath = 'https://graph.facebook.com/oauth/access_token';
    protected static $apiPath = 'https://graph.facebook.com';
    protected static $redirectUri = 'http://undone.com/facebook/redirect';
    protected static $responseType = 'code';
    protected static $scope = 'email';
    protected static $apiVersion = '5.60';

    /**
     * Отпарсить полученный access_token в однотипный формат
     * 
     * Пример ответа Facebook сервера:
     *
     * "access_token=EAA3cstzP82wFZA6dYkFXkG4suDsb7OZATlsfQZDZD&expires=5181689"
     * 
     * @param $accessToken accessToken в каком либо виде, полученный от сервера
     * 
     * @return Array
     */
    protected function parseAccessTockenResponseAfterReceiving($response)
    {
        // Take the string and return an Array
        // Parse string "access_token=kFXkG4suDsb7OZATlsfQZDZD&expires=5181689" to array and return it
        parse_str ($response, $out);

        return (array) $out;
    }

    /**
     * 
     * @param $collection
     * 
     * @return Array
     */
    protected function prepareUserCollection(Collection $collection) {
        $returnCollection = collect([]);

        $returnCollection->put('id', $collection->get('id', null));
        $returnCollection->put('email', $collection->get('email', null));
        $returnCollection->put('fname', $collection->get('first_name', null));
        $returnCollection->put('lname', $collection->get('last_name', null));
        $returnCollection->put('sex', $collection->get('gender', null));
        $returnCollection->put('photo', $collection->get('picture', null)['data']['url']);
        $returnCollection->put('photo_small', null);

        return $returnCollection;
    }

    /**
     *  get user
     *
     *  @param int $vkId            user's vk id
     *  @param string(hash) $token  access_token from vk server
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
            'access_token' => $accessToken['access_token'],
        );
        
        $url = static::$apiPath . '/me?' . urldecode(http_build_query($params));

        if ($response = $this->curlRequestGet($url))
        {
            $response = collect(json_decode($response, true));

            $fields = [
                'fields' => 'id,first_name,last_name,birthday,picture,gender,email',
                'access_token' => $accessToken['access_token'],
            ];

            $url = static::$apiPath  . '/' . $response['id'] . '?' . urldecode(http_build_query($fields));
            
            $response = $this->curlRequestGet($url);
            parse_str ($response, $out);

            $collection = collect($out = json_decode($response, true));

            return $this->prepareUserCollection($collection);
        }

        return false;
    }
}
