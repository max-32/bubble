<?php

namespace App\Http\Controllers\Auth\Instagram;

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

class InstagramController extends OAuth20Controller
{
    // property

    // static

    // rewrite parent defaults
    
    protected static $clientSecret = '4285d29843f64de0b3d21608d4d9d25b';
    protected static $clientId = '85b888fe08d348dfb4fba50bc4dcf678';
    protected static $authPath = 'https://api.instagram.com/oauth/authorize';
    protected static $accessTokenPath = 'https://api.instagram.com/oauth/access_token';
    protected static $apiPath = 'https://api.instagram.com/v1/users/self';
    protected static $redirectUri = 'http://undone.com/instagram/redirect';
    protected static $responseType = 'code';
    protected static $scope = 'public_content';
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
     * Распарсить имя и фамилию пользователя
     * 
     * @param $accessToken accessToken в каком либо виде, полученный от сервера
     * 
     * @return Array
     */
    protected function parseName($string)
    {
        $fname = null;
        $lname = null;

        $re = '/(\w+)\s(\w+)/';
        $str = $string;

        preg_match_all($re, $str, $matches);

        if (isset($matches[1]) and isset($matches[1][0]) and count($matches[1][0]) == 1) {
            $fname = $matches[1][0];
        }
        if (isset($matches[2]) and isset($matches[2][0]) and count($matches[2][0]) == 1) {
            $lname = $matches[2][0];
        }

        return ['fname' => $fname, 'lname' => $lname];
    }

    /**
     * 
     * @param $collection
     * 
     * @return Array
     */
    protected function prepareUserCollection(Collection $collection) {
        $returnCollection = collect([]);

        $names = $this->parseName($collection['full_name']);

        $returnCollection->put('id', $collection->get('id', null));
        $returnCollection->put('email', $collection->get('email', null));
        $returnCollection->put('fname', $names['fname']);
        $returnCollection->put('lname', $names['lname']);
        $returnCollection->put('sex', $collection->get('gender', null));
        $returnCollection->put('photo', $collection->get('profile_picture', null));
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

            $collection = ($response->has('data')) ?
                    collect($response['data']) :
                    false;

            return $this->prepareUserCollection($collection);
        }

        return false;
    }
}
