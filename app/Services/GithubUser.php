<?php

namespace App\Services;

use App\Services\ApiClient;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
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

            $this->fetchRepositories();

            return true;
        }
        
        return false;
    }

    /**
     * Retrieve repositories of a User
     */
    private function fetchRepositories()
    {
        // Import the user's repositories ONCE
        if (auth()->user()->repositories->isEmpty()) {

            $repositories = $this->getApi('/user/repos', array());
            
            foreach($repositories as $repository){
                auth()->user()->repositories()->create([
                    'repository_id' => $repository['id'],
                    'repository_name' => $repository['name'],
                    'repository_fullname' => $repository['full_name'],
                    'repository_private' => $repository['private'],
                    'repository_url' => $repository['html_url'],
                ]);
            }
        }
    }
}
