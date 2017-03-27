<?php

namespace App\Http\Controllers;

use Auth;
use Session;
use Redirect;
use App\User;
use Illuminate\Http\Request;

class SignoutController extends Controller
{
	public function signout()
	{
        Auth::logout();         # log out
        Session::flush();       # clean session
        Session::save();        # save empty session's state

        return Redirect::to('/');
	}
}
