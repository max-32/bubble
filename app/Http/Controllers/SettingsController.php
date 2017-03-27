<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
	public function settings()
	{
		return view('debug/settings')
			->with('user', Auth::user());
	}
}
