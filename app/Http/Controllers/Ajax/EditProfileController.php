<?php

namespace App\Http\Controllers\Ajax;

use App\User;
use Response;
use App\UserInfo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection as Collection;

class EditProfileController extends Controller
{
    /**
     * Свойства, которые с клиента не меняются
     *
     * @var Array
     */
    private static $hiddenInputProperties = [
        'created_at', 'updated_at', 'registration_date'
    ];

    /**
     * Свойства, которые не могут быть пустыми
     *
     * @var Array
     */
    private static $notEmptyAcceptableProperties = [
        'fname', 'lname',
    ];

    /**
     * Filtered Input values of request
     *
     * @var Collection
     */
    private $input = null;


    /**
     * Конструктор
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        // filtering....
        $this->input = $this->filterInputValues( collect($request->input()) );
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $user = $request->user();
        $input = $this->input;
        $changes = collect([]);

        # check input
        $input->each(function($value, $key) use ($user, $changes)
        {
            $userValue = $user->info->$key;                 # value of auth user
            $inputValue = $this->fixInputValue($value);     # input value

            if ($userValue == $inputValue) {
                return null;
            } else {
                // input value is different than db value
                // seems like we need to change it 
                $changes[$key] = $inputValue;
            }
        });


        # save changes
        $userInfo = $user->info;

        // apply changes
        $changes->each(function($value, $key) use ($user, $userInfo)
        {
            if ( ! empty($value)) {
                $userInfo[$key] = $value;
            }
            else {
                $userInfo[$key] = null;
            }
        });

        // write to db
        $userInfo->save();

        # return new (chnaged) object
        return Response::json([
            'user' => $userInfo->toArray(),     # updated user
            'changed' => $changes->toArray()    # affected properties
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }





    /**
     * Sanitaze input value
     *
     * @param mixed  $value - Value to sanitize
     * @return mixed
     */
    private function fixInputValue($value)
    {
        return trim($value);
    }

    /**
     * Sanitaze input values
     *
     * @param  Collection $input - Input request values
     * @return Collection
     */
    private function filterInputValues(Collection $input)
    {
        // little clearing
        $input->each(function($value, $key) {
            $value = $this->fixInputValue($value);
        });

        $input = $input->reject(function($value, $key)
        {
            // created_at, updated_at .... are not permitted to be cchanged from client side
            if (in_array($key, static::$hiddenInputProperties)) return true;

            // if a value is empty we should check is it acceptable to be empty...
            if (empty($value)) {
                if (in_array($key, static::$notEmptyAcceptableProperties)) return true;
            }
        });

        return $input;
    }
}
