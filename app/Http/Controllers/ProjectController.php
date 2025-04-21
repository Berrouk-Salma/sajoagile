<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = auth()->user()->projects;
        return view('projects.index', compact('projects'));
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
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
