<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\TeamRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{
    protected $teamRepository;
    protected $userRepository;

    /**
     * TeamController constructor.
     *
     * @param TeamRepositoryInterface $teamRepository
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        TeamRepositoryInterface $teamRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->teamRepository = $teamRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the teams.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $teams = $this->teamRepository->all();
        return response()->json($teams);
    }

    /**
     * Store a newly created team in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $team = $this->teamRepository->create([
            'name' => $request->name,
        ]);

        // Add the current user to the team
        $this->teamRepository->addMember($team->team_id, $request->user()->id);

        return response()->json($team, 201);
    }

    /**
     * Display the specified team.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $team = $this->teamRepository->find($id);
            return response()->json($team);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Team not found'], 404);
        }
    }

    /**
     * Update the specified team in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $team = $this->teamRepository->find($id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Team not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $this->teamRepository->update([
            'name' => $request->name,
        ], $id);

        $team = $this->teamRepository->find($id);
        return response()->json($team);
    }

    /**
     * Remove the specified team from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $this->teamRepository->delete($id);
            return response()->json(['message' => 'Team deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting team'], 500);
        }
    }

    /**
     * Get all members of a team.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMembers($id)
    {
        try {
            $members = $this->teamRepository->getMembers($id);
            return response()->json($members);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Team not found'], 404);
        }
    }

    /**
     * Add a member to the team.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function addMember(Request $request, $id)
    {
        try {
            $team = $this->teamRepository->find($id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Team not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $this->teamRepository->addMember($id, $request->user_id);

        return response()->json(['message' => 'Member added successfully']);
    }

    /**
     * Remove a member from the team.
     *
     * @param  int  $id
     * @param  int  $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeMember($id, $userId)
    {
        try {
            $team = $this->teamRepository->find($id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Team not found'], 404);
        }

        try {
            $user = $this->userRepository->find($userId);
        } catch (\Exception $e) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $this->teamRepository->removeMember($id, $userId);

        return response()->json(['message' => 'Member removed successfully']);
    }

    /**
     * Get teams by user.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTeamsByUser($userId)
    {
        try {
            $user = $this->userRepository->find($userId);
        } catch (\Exception $e) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $teams = $this->teamRepository->getTeamsByUser($userId);
        return response()->json($teams);
    }

    /**
     * Get teams by project.
     *
     * @param  int  $projectId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTeamsByProject($projectId)
    {
        $teams = $this->teamRepository->getTeamsByProject($projectId);
        return response()->json($teams);
    }
}
