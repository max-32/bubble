<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Auth\Vk\VkController as VkAuth;
use App\Http\Controllers\Auth\Google\GoogleController as GoogleAuth;
use App\Http\Controllers\Auth\Facebook\FacebookController as FacebookAuth;
use App\Http\Controllers\Auth\Instagram\InstagramController as InstagramAuth;

class SignupController extends Controller
{
	public function signup()
	{
        return view('debug/signup')
	        ->with('vk_auth_link', VkAuth::generateAuthLink())
	        ->with('google_auth_link', GoogleAuth::generateAuthLink())
	        ->with('facebook_auth_link', FacebookAuth::generateAuthLink())
	        ->with('instagram_auth_link', InstagramAuth::generateAuthLink());
	}
}
