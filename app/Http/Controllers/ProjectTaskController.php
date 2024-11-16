<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectTask;
use App\Http\Requests\StoreProjectTaskRequest;
use App\Http\Requests\UpdateProjectTaskRequest;
use Symfony\Component\HttpFoundation\Response;

class ProjectTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectTaskRequest $request, Project $project)
    {
        try {

            // if (auth()->user()->id !== $project->owner_id) {
            //     throw new \Exception('Error!', Response::HTTP_FORBIDDEN);
            // }
            $project->tasks()->create($request->validated());

            return response($project->tasks, Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return response($e->getMessage(), $e->getCode());

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ProjectTask $projectTask)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProjectTask $projectTask)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectTaskRequest $request, Project $project, ProjectTask $projectTask)
    {
        $projectTask->update($request->validated());

        return response($projectTask, Response::HTTP_OK);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProjectTask $projectTask)
    {
        //
    }
}
