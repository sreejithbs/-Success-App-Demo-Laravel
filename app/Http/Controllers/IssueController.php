<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\IssueRequest;
use App\Events\IssueWasCreated;
use App\Events\IssueWasUpdated;
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

        // Import the user's repository issues manually for the first time
        if(! $repository->issues_imported && $this->githubUser->fetchIssues($repository) ){
            $repository->issues_imported = true;
            $repository->save();
            $repository->refresh();
        }
        
        return view('backend.pages.issues.list', with(['repository' => $repository]));
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $issue_uid)
    {
        if($request->ajax()){
            $issue = Issue::where('uid', $issue_uid)->firstOrFail();
            
            $response = array('status' => true, 'issue' => $issue);
            return response()->json($response);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\IssueRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(IssueRequest $request, $issue_uid)
    {
        if($request->ajax()){
            $issue = Issue::where('uid', $issue_uid)->firstOrFail();
            $issue->title = $request->input('issue-title');
            $issue->description = $request->input('issue-description');
            $issue->save();

            // Dispatch Event
            IssueWasUpdated::dispatch($issue);

            $response = array('status' => true, 'message' => 'Issue has been updated successfully');
            return response()->json($response);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function delete($issue_uid)
    {
        $issue = Issue::where('uid', $issue_uid)->firstOrFail();
        $issue->delete();
        
        return back()->with('success', "Issue has been deleted successfully");
    }
}
