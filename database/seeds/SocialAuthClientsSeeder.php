<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class SocialAuthClientsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$authClients = [
    		[
    			'client_id' => 1,
    			'client_name' => 'vk',
    			'created_at' => Carbon::now()->toDateTimeString(),
    		],
    		[
    			'client_id' => 2,
    			'client_name' => 'facebook',
    			'created_at' => Carbon::now()->toDateTimeString(),
    		],
    		[
    			'client_id' => 3,
    			'client_name' => 'instagram',
    			'created_at' => Carbon::now()->toDateTimeString(),
    		],
    		[
    			'client_id' => 4,
    			'client_name' => 'google',
    			'created_at' => Carbon::now()->toDateTimeString(),
    		],
    	];

    	foreach ($authClients as $client) {
	    	DB::table('social_auth_clients')->insert($client);
    	}

    }
}
