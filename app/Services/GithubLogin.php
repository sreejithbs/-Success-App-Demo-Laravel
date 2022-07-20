<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Services\GithubUser;

/**
 * Github Authentication
 */
class GithubLogin
{
    // GitHub API Oauth Base URL
    const OAUTH_API_BASE_URL = 'https://github.com/login/oauth/';

    private $githubUser;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(GithubUser $githubUser)
    {
        $this->githubUser = $githubUser;
    }

    /**
     * Request a user's GitHub identity
     *
     * @return  string
     */
    public function redirect()
    {
        return self::OAUTH_API_BASE_URL . 'authorize?' . http_build_query([
            'client_id' => config('services.github.client_id'),
            'redirect_uri' => config('services.github.redirect_uri'),
            'scope' => config('services.github.scopes'),
        ]);
    }

    /**
     * GitHub authentication
     *
     * @param   string  $code
     * @return  boolean
     */
    public function authenticate($code)
    {
        $access_token = false;
        $response = Http::acceptJson()->post(self::OAUTH_API_BASE_URL . 'access_token', [
            'client_id' => config('services.github.client_id'),
            'client_secret' => config('services.github.client_secret'),
            'code' => $code,
            'redirect_uri' => config('services.github.redirect_uri'),
        ]);

        if( $response->successful() ){
            $result = json_decode($response->body(), true);
            if( array_key_exists('access_token', $result) ){
                $access_token = $result['access_token'];
            }
        }

        return $this->githubUser->createOrUpdateUser($access_token);
    }
}
