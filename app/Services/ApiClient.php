<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

/**
 * Abstract class for Github API
 */
abstract class ApiClient
{
    // GitHub API Base URL
    const API_BASE_URL = 'https://api.github.com';

    /**
     * GET API method
     *
     * @param   string  $path
     * @param   array   $parameters
     * @return  array
     */
    protected function getApi($path, array $parameters = array())
    {
        $response = Http::withHeaders([
            'Accept' => 'application/vnd.github+json',
            'Authorization' => 'token ' . auth()->user()->github_token,
        ])->get(self::API_BASE_URL . $path, $parameters);

        $result = array();
        if( $response->successful() ){
            $result = json_decode($response->body(), true);
        }

        return $result;
    }

     /**
     * POST API method
     *
     * @param   string  $path
     * @param   array   $parameters
     * @return  array
     */
    protected function postApi($path, array $parameters = array())
    {
        $response = Http::withHeaders([
            'Accept' => 'application/vnd.github+json',
            'Authorization' => 'token ' . auth()->user()->github_token,
        ])->post(self::API_BASE_URL . $path, $parameters);

        $result = array();
        if( $response->successful() ){
            $result = json_decode($response->body(), true);
        }

        return $result;
    }
}