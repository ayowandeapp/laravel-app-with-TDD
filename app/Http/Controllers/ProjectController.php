<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\User;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Symfony\Component\HttpFoundation\Response;

class ProjectController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $projects = auth()->user()->accessibleProjects();
        return response($projects, Response::HTTP_OK);
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
    public function store(StoreProjectRequest $request): Response
    {
        $attributes = $request->validated();

        $project = auth()->user()->projects()->create($attributes);

        if ($request->has('tasks')) {
            $project->tasks()->createMany($request['tasks']);
        }

        return response($project, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        if (auth()->user()->isNot($project->owner)) {
            return response('unAutorized', Response::HTTP_FORBIDDEN);
        }
        return response($project, Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        //using policy
        // if ($request->user()->cannot('update', $project)) {
        //     abort(403);
        // }
        $project->update($request->validated());

        return response($project, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        // if (auth()->user()->cannot('delete', $project)) {
        //     abort(302);
        // }
        $this->authorize('manage', $project);

        $project->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function authorize($type, $project)
    {
        if (auth()->user()->cannot($type, $project)) {
            abort(302);
        }

    }
}
