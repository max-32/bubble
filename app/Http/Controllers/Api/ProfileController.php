<?php

namespace App\Http\Controllers\Api;

use DB;
use App\User;
use App\UserInfo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    /**
     * Пол пользователей
     *
     * @return Arrayable
     */
	public function genderList()
	{
		return DB::table('users_sex')->take(6)->get();
	}

    /**
     * Информация о пользователе
     *
     * @param Int $id - ID пользователя
     *
     * @return Collection
     */
	public function userById(Int $id)
	{
		return UserInfo::where('user_id', $id)->first();
	}

    /**
     * Информация о пользователях
     *
     * @param Object $request - запрос
     *
     * @return []
     */
	public function usersById(Request $request)
	{
		$listId = (array) $request->input('users') ?? [];

		if (is_array($listId) and ! empty($listId))
		{
			return UserInfo::whereIn('user_id', $listId)->take(40)->get();
		}

		return [];
	}
}
