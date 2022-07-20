<?php

namespace App\Services;

use App\Services\ApiClient;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

/**
 * Github User Access
 */
class GithubUser extends ApiClient
{
    /**
     * Create or Update User
     *
     * @param   string  $access_token
     * @return  boolean
     */
    public function createOrUpdateUser($access_token)
    {
        if( ! $access_token ){
            return false;
        }

        $response = Http::withHeaders([
            'Authorization' => 'token ' . $access_token,
        ])->get(self::API_BASE_URL . '/user');

        if( $response->successful() ){
            $result = json_decode($response->body(), true);

            $user = User::updateOrCreate([
                'github_id' => $result['id'],
            ], [
                'name' => $result['name'],
                'username' => $result['login'],
                'github_token' => $access_token,
                'github_url' => $result['html_url'],
            ]);

            Auth::login($user);

            return true;
        }
        
        return false;
    }
}
