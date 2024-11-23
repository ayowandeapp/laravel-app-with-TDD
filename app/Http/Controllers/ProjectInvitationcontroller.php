<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectInvitationRequest;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProjectInvitationcontroller extends Controller
{

    public function storeInvitedUser(StoreProjectInvitationRequest $request, Project $project)
    {
        $invitee = User::whereEmail($request->get('email'))->firstOrFail();

        $project->invite($invitee);

        return response($project->refresh(), Response::HTTP_CREATED);

    }
}
