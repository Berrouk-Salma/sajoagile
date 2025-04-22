<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use App\Models\UserProject;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        $projectsCreated = $user->projects;

        $projectsJoined = UserProject::where('user_id', $user->id)
            ->where('status', 'accepted')
            ->with('project')
            ->get()
            ->pluck('project');

        return view('projects.index', [
            'projectsCreated' => $projectsCreated,
            'projectsJoined' => $projectsJoined,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'min:3', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
        ]);

        // dd($request->all());
        auth()->user()->projects()->create([
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => \Carbon\Carbon::parse($request->start_date)->format('Y-m-d'),
            'end_date' => \Carbon\Carbon::parse($request->end_date)->format('Y-m-d'),
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $project = Project::findOrFail($id);
        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $project = Project::findOrFail($id);
        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'min:3', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
        ]);

        try {
            $project = Project::findOrFail($id);

            $project->update([
                'name' => $request->name,
                'description' => $request->description,
                'start_date' => \Carbon\Carbon::parse($request->start_date)->format('Y-m-d'),
                'end_date' => \Carbon\Carbon::parse($request->end_date)->format('Y-m-d'),
            ]);

            return redirect()->route('projects.index')->with('success', 'Projet mis à jour avec succès.');
        } catch (\Exception $e){
            return redirect()->route('projects.index')->with('error', 'Une erreur est survenue lors de la mise à jour du projet.');
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $project = Project::findOrFail($id);

        if (auth()->user()->id !== $project->user_id) {
            return redirect()->route('projects.index')->with('error', 'Vous n\'avez pas la permission de supprimer ce projet.');
        }

        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Le projet a été supprimé avec succes.');
    }
}
