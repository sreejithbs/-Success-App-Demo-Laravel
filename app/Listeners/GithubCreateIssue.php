<?php

namespace App\Listeners;

use App\Events\IssueWasCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Services\GithubUser;

class GithubCreateIssue implements ShouldQueue
{
    private $githubUser;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(GithubUser $githubUser)
    {
        $this->githubUser = $githubUser;
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\IssueWasCreated  $event
     * @return void
     */
    public function handle(IssueWasCreated $event)
    {
        $issue = $event->issue;
        
        $result = $this->githubUser->createIssue($issue);

        if (count($result) > 0 && array_key_exists('id', $result)) {
            $issue->uid = $result['id'];
            $issue->reference_url = $result['html_url'];
            $issue->save();
        }
    }
}
