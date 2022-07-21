<?php

namespace App\Http\Controllers;

use App\Http\Requests\IssueRequest;
use App\Events\IssueWasCreated;
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
        $repository = Repository::with(['issues' => function ($q) {
            $q->orderBy('uid', 'desc');
        }])->where('uid', $repository_uid)->firstOrFail();

        if($repository){

            // Import the user's repository issues manually for the first time
            if(! $repository->issues_imported && $this->githubUser->fetchIssues($repository) ){
                $repository->issues_imported = true;
                $repository->save();
            }
            
            return view('backend.pages.issues.list', with(['repository' => $repository]));
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
     * @param  \App\Http\Requests\IssueRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(IssueRequest $request)
    {
        if($request->ajax()){
            $repository = Repository::where('uid', $request->input('repository-uid'))->firstOrFail();
            if ($repository) {

                $issue = new Issue;
                $issue->title = $request->input('issue-title');
                $issue->description = $request->input('issue-description');
                $issue->status = 'open';
                $repository->issues()->save($issue);
                $repository->refresh();

                // Dispatch Event
                IssueWasCreated::dispatch($issue);

                $response = array('status' => true, 'message' => 'Issue has been recorded successfully');
                return response()->json($response);
            }
        }

        $response = array('status' => false, 'message' => 'Oops! Something went wrong');
        return response()->json($response);
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
