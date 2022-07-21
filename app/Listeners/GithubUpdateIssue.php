<?php

namespace App\Listeners;

use App\Events\IssueWasUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Services\GithubUser;

class GithubUpdateIssue implements ShouldQueue
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
     * @param  \App\Events\IssueWasUpdated  $event
     * @return void
     */
    public function handle(IssueWasUpdated $event)
    {
        $issue = $event->issue;
        $result = $this->githubUser->updateIssue($issue);
    }
}
