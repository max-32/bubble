<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
	public function profile(User $user)
	{
		$userAuth = Auth::user();
		$userCurrent = $user;

        return view('debug/profile')
	        ->with('isOwner', $userAuth->id == $userCurrent->id)
	        ->with('userAuth', $userAuth)
	        ->with('userCurrent', $userCurrent);
	}
}
