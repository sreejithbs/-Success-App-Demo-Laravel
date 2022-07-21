<?php

namespace App\Services;

use App\Services\ApiClient;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;

use App\Models\User;
use App\Models\Repository;
use App\Models\Issue;

/**
 * Github User Access
 */
class GithubUser extends ApiClient
{
    /**
     * Retrieve User details
     *
     * @param   string  $access_token
     * @return  boolean
     */
    public function fetchUser($access_token)
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

            // Import the user's repositories manually for the first time
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
        if (auth()->user()->repositories->isEmpty()) {

            $repositories = $this->getApi('/user/repos');
            
            foreach($repositories as $repository){
                auth()->user()->repositories()->create([
                    'uid' => $repository['id'],
                    'name' => $repository['name'],
                    'full_name' => $repository['full_name'],
                    'visibility' => $repository['private'] ? 'private' : 'public',
                    'reference_url' => $repository['html_url'],
                ]);
            }
        }
    }

    /**
     * Retrieve issues related to the repository
     * 
     * @param  \App\Models\Repository  $repository
     * @return  boolean
     */
    public function fetchIssues(Repository $repository)
    {
        $issues = $this->getApi('/repos/' .$repository->full_name. '/issues');

        foreach($issues as $issue){
            $repository->issues()->create([
                'uid' => $issue['id'],
                'title' => $issue['title'],
                'description' => $issue['body'],
                'status' => $issue['state'],
                'reference_url' => $issue['html_url'],
                'is_synced' => true,
            ]);
        }

        return true;
    }

    /**
     * Create new issues within a repository
     * 
     * @param  \App\Models\Issue  $repository
     * @return  array
     */
    public function createIssue(Issue $issue)
    {
        return $this->postApi('/repos/' .$issue->repository->full_name. '/issues', [
            'title' => $issue->title,
            'body' => $issue->description
        ]);
    }
}
