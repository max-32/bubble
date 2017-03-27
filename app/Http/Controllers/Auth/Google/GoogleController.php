<?php

namespace App\Http\Controllers\Auth\Google;

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

// Авторизация Google типа Authorization Code Flow
// https://developers.google.com/adwords/api/docs/guides/authentication?hl=ru#webapp

class GoogleController extends OAuth20Controller
{
    // property

    // static

    // rewrite parent defaults
    
    protected static $clientSecret = '7uV6Xuz0ykrsFg3TxrM0w5JG';
    protected static $clientId = '1060763024649-u860j5qqsa274vntm6joa6bth5jej9hb.apps.googleusercontent.com';
    protected static $authPath = 'https://accounts.google.com/o/oauth2/auth';
    protected static $accessTokenPath = 'https://accounts.google.com/o/oauth2/token';
    protected static $apiPath = 'https://www.googleapis.com/oauth2/v1/userinfo';
    protected static $redirectUri = 'http://undone.com/google/redirect';
    protected static $responseType = 'code';
    protected static $scope = 'email';
    protected static $apiVersion = null;

    /**
     * Отпарсить полученный access_token в однотипный формат
     * 
     * Пример ответа Google сервера:
     *
     * {
     *  "access_token" : "ya29.Ci-5A6ABA6_8wCc3DlI5KWjED_HQKCrbGGeYCgGQPPhUe1abawYfyTW1EJSkBpQQZA",
     *  "expires_in" : 3598,
     *  "id_token" : "eyJhbGXLnQiCr_vz1f7x_VqnTcgTLjOyHFllC5iiTKbuzrhm3ixXA",
     *  "token_type" : "Bearer"
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

        $returnCollection->put('id', $collection->get('id', null));
        $returnCollection->put('email', $collection->get('email', null));
        $returnCollection->put('fname', $collection->get('given_name', null));
        $returnCollection->put('lname', $collection->get('family_name', null));
        $returnCollection->put('sex', $collection->get('gender', null));
        $returnCollection->put('photo', $collection->get('picture', null));
        $returnCollection->put('photo_small', null);

        return $returnCollection;
    }

    /**
     *  get user via google api
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
        
        $url = static::$apiPath . '?' . urldecode(http_build_query($params));

        if ($response = $this->curlRequestGet($url))
        {
            $response = collect(json_decode($response, true));

            $collection = ($response->has('id')) ?
                    $response :
                    false;

            return $this->prepareUserCollection($collection);
        }

        return false;
    }
}
