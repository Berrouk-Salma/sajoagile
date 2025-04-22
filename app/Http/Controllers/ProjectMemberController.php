<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserProject;
use Illuminate\Http\Request;

class ProjectMemberController extends Controller
{
    private function findUserByEmailOrName($keyword)
    {
        return User::where(function ($query) use ($keyword) {
            $query->where('name', 'like', '%' . $keyword . '%')
                ->orWhere('email', 'like', '%' . $keyword . '%');
        })->firstOrFail();
    }

    public function searchUsers(Request $request)
    {
        $keyword = $request->input('keyword');

        $users = User::where(function ($query) use ($keyword) {
            $query->where('name', 'like', '%' . $keyword . '%')
                ->orWhere('email', 'like', '%' . $keyword . '%');
        })->get();

        return response()->json($users);
    }

    public function inviteUsers(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'email' => 'required|email',
        ]);

        $user = $this->findUserByEmailOrName($request->input('email'));
        $projectId = $request->input('project_id');

        $existing = UserProject::where('user_id', $user->id)
            ->where('project_id', $projectId)
            ->first();

        if ($existing) {
            return response()->json(['message' => 'Utilisateur déjà invité ou membre.'], 409);
        }

        UserProject::create([
            'user_id' => $user->id,
            'project_id' => $projectId,
            'status' => 'pending',
        ]);

        return response()->json(['message' => 'Invitation envoyée avec succès.']);
    }

    public function showInvitations()
    {
        $user = auth()->user();

        $invitations = UserProject::with('project')
            ->where('user_id', $user->id)
            ->get();

        return view('projects.invitations', compact('invitations'));
    }

    public function respondToInvitation(Request $request, $projectId)
    {
        $request->validate([
            'action' => 'required|in:accept,decline',
        ]);

        $user = $request->user();

        $userProject = UserProject::where('user_id', $user->id)
            ->where('project_id', $projectId)
            ->where('status', 'pending')
            ->firstOrFail();

        $userProject->status = $request->input('action') === 'accept' ? 'accepted' : 'declined';
        $userProject->save();

        return response()->json([
            'message' => 'Réponse enregistrée.'
        ]);
    }
}
