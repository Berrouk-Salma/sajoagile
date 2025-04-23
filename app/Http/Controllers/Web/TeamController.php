<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\TeamRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\ProjectRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{
    protected $teamRepository;
    protected $userRepository;
    protected $projectRepository;

    /**
     * TeamController constructor.
     *
     * @param TeamRepositoryInterface $teamRepository
     * @param UserRepositoryInterface $userRepository
     * @param ProjectRepositoryInterface $projectRepository
     */
    public function __construct(
        TeamRepositoryInterface $teamRepository,
        UserRepositoryInterface $userRepository,
        ProjectRepositoryInterface $projectRepository
    ) {
        $this->teamRepository = $teamRepository;
        $this->userRepository = $userRepository;
        $this->projectRepository = $projectRepository;
    }

    /**
     * Display a listing of the teams.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // For regular users, show only their teams
        if (Auth::user()->role !== 'admin') {
            $teams = $this->teamRepository->getTeamsByUser(Auth::id());
        } else {
            // For admins, show all teams
            $teams = $this->teamRepository->all();
        }
        
        return view('teams.index', compact('teams'));
    }

    /**
     * Show the form for creating a new team.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('teams.create');
    }

    /**
     * Store a newly created team in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $team = $this->teamRepository->create([
            'name' => $request->name,
        ]);

        // Add the current user to the team
        $this->teamRepository->addMember($team->team_id, Auth::id());

        return redirect()->route('teams.show', $team->team_id)
            ->with('success', 'Team created successfully.');
    }

    /**
     * Display the specified team.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        try {
            $team = $this->teamRepository->find($id);
            $members = $this->teamRepository->getMembers($id);
            $projects = $this->projectRepository->getProjectsByTeam($id);
            
            return view('teams.show', compact('team', 'members', 'projects'));
        } catch (\Exception $e) {
            return redirect()->route('teams.index')
                ->with('error', 'Team not found.');
        }
    }

    /**
     * Show the form for editing the specified team.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        try {
            $team = $this->teamRepository->find($id);
            return view('teams.edit', compact('team'));
        } catch (\Exception $e) {
            return redirect()->route('teams.index')
                ->with('error', 'Team not found.');
        }
    }

    /**
     * Update the specified team in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $team = $this->teamRepository->find($id);
        } catch (\Exception $e) {
            return redirect()->route('teams.index')
                ->with('error', 'Team not found.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $this->teamRepository->update([
            'name' => $request->name,
        ], $id);

        return redirect()->route('teams.show', $id)
            ->with('success', 'Team updated successfully.');
    }

    /**
     * Remove the specified team from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $this->teamRepository->delete($id);
            return redirect()->route('teams.index')
                ->with('success', 'Team deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('teams.index')
                ->with('error', 'Error deleting team.');
        }
    }

    /**
     * Display team members.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function showMembers($id)
    {
        try {
            $team = $this->teamRepository->find($id);
            $members = $this->teamRepository->getMembers($id);
            $availableUsers = $this->userRepository->all()->filter(function ($user) use ($members) {
                return !$members->contains('id', $user->id);
            });
            
            return view('teams.members', compact('team', 'members', 'availableUsers'));
        } catch (\Exception $e) {
            return redirect()->route('teams.index')
                ->with('error', 'Team not found.');
        }
    }

    /**
     * Add a member to the team.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addMember(Request $request, $id)
    {
        try {
            $team = $this->teamRepository->find($id);
        } catch (\Exception $e) {
            return redirect()->route('teams.index')
                ->with('error', 'Team not found.');
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $this->teamRepository->addMember($id, $request->user_id);

        return redirect()->route('teams.members', $id)
            ->with('success', 'Member added successfully.');
    }

    /**
     * Remove a member from the team.
     *
     * @param  int  $id
     * @param  int  $userId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeMember($id, $userId)
    {
        try {
            $team = $this->teamRepository->find($id);
        } catch (\Exception $e) {
            return redirect()->route('teams.index')
                ->with('error', 'Team not found.');
        }

        try {
            $user = $this->userRepository->find($userId);
        } catch (\Exception $e) {
            return redirect()->route('teams.members', $id)
                ->with('error', 'User not found.');
        }

        // Prevent removing the last member
        $members = $this->teamRepository->getMembers($id);
        if ($members->count() <= 1) {
            return redirect()->route('teams.members', $id)
                ->with('error', 'Cannot remove the last member from the team.');
        }

        $this->teamRepository->removeMember($id, $userId);

        return redirect()->route('teams.members', $id)
            ->with('success', 'Member removed successfully.');
    }
}
