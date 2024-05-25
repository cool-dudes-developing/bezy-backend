<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Models\User;
use App\Notifications\InvitationNotification;
use Illuminate\Http\Request;

class ProjectMemberController extends Controller
{
    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'role' => ['nullable', 'in:viewer,editor']
        ]);

        // check if user is owner of project
        if (!$project->members()->wherePivot('role', 'owner')->find(auth()->id())) {
            return $this->respondWithError('Only owner can invite members');
        }

        // check if user already registered
        if (!$user = User::where('email', $validated['email'])->first()) {
            $user = User::create([
                'name' => $validated['email'],
                'email' => $validated['email'],
                'password' => null
            ]);
        }

        // check if user already member of project
        if ($project->members->contains($user)) {
            return $this->respondWithError('User already member of project');
        }

        $user->notify(new InvitationNotification($project));

        $project->members()->attach($user, ['role' => $validated['role'] ?? 'viewer']);
        return $this->respondWithSuccess('User invited to project', ProjectResource::make($project->load('members')));
    }

    public function update(Request $request, Project $project, User $user)
    {
        // check if user is owner of project
        if (!$project->members()->wherePivot('role', 'owner')->find(auth()->id())->exists()) {
            return $this->respondWithError('Only owner can update members');
        }

        // check if user is member of project
        if (!$project->members->contains($user)) {
            return $this->respondWithError('User not member of project');
        }

        $validated = $request->validate([
            'role' => ['required', 'in:viewer,editor']
        ]);

        $project->members()->updateExistingPivot($user, ['role' => $validated['role']]);
        return $this->respondWithSuccess('User role updated', ProjectResource::make($project->load('members')));
    }

    public function destroy(Project $project, User $user)
    {
        // check if user is owner of project
        if (!$project->members()->wherePivot('role', 'owner')->find(auth()->id())->exists()) {
            return $this->respondWithError('Only owner can remove members');
        }

        // check if user is member of project
        if (!$project->members->contains($user)) {
            return $this->respondWithError('User not member of project');
        }

        $project->members()->detach($user);
        return $this->respondWithSuccess('User removed from project');
    }

    public function accept(Project $project)
    {
        $project->members()->updateExistingPivot(auth()->id(), ['accepted_at' => now()]);
        return $this->respondWithSuccess('User accepted to project', ProjectResource::make($project->load('members')));
    }

    public function reject(Project $project)
    {
        $project->members()->detach(auth()->id());
        return $this->respondWithSuccess('User rejected from project');
    }
}
