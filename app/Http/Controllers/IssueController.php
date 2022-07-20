<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIssueRequest;
use App\Http\Requests\UpdateIssueRequest;
use App\Services\GithubUser;
use App\Models\Issue;
use App\Models\Repository;

class IssueController extends Controller
{
    private $githubUser;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(GithubUser $githubUser)
    {
        $this->middleware('auth');
        $this->githubUser = $githubUser;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($repository_uid)
    {
        $repository = Repository::where('uid', $repository_uid)->firstOrFail();
        if($repository){

            // Import the user's repository issues manually ONCE for the first time
            if(! $repository->issues_imported){
                $this->githubUser->fetchIssues($repository);
                $repository->issues_imported = true;
                $repository->save();
            }
            
            $issues = $repository->issues;

            return view('backend.pages.issues.list', with(['issues' => $issues, 'repository' => $repository]));
        }
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
     * @param  \App\Http\Requests\StoreIssueRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreIssueRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Issue  $issue
     * @return \Illuminate\Http\Response
     */
    public function show(Issue $issue)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Issue  $issue
     * @return \Illuminate\Http\Response
     */
    public function edit(Issue $issue)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateIssueRequest  $request
     * @param  \App\Models\Issue  $issue
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateIssueRequest $request, Issue $issue)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Issue  $issue
     * @return \Illuminate\Http\Response
     */
    public function destroy(Issue $issue)
    {
        //
    }
}
