<?php

namespace App\Http\Controllers;

use Auth;
use Redirect;
use Illuminate\Http\Request;

class IndexController extends Controller
{
	public function index()
	{
        if (Auth::check()) {
            return Redirect::route('profile', [Auth::user()->id]);
        } else {
            return Redirect::route('signup');
        }
	}
}
