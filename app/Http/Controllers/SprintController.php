<?php

namespace App\Http\Controllers;

use App\Models\Sprint;
use App\Models\Project;
use Illuminate\Http\Request;

class SprintController extends Controller
{
    //public function show(Project $project)
    //{
        //$sprints = $project->sprints;
        //return view('projects.index', compact('sprints'));
    //}

    public function create(Project $project)
    {
        return view('sprints.create', compact('project'));
    }

    public function store(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        //dd($request->all());

        Sprint::create([
            'name' => $request->name,
            'start_date' => \Carbon\Carbon::parse($request->start_date)->format('Y-m-d'),
            'end_date' => \Carbon\Carbon::parse($request->end_date)->format('Y-m-d'),
            'project_id' => $project->id,
        ]);

        //dd($request->all());

        return redirect()->route('projects.show', $project);
    }

    public function edit(Sprint $sprint){
        return view('sprints.edit', compact('sprint'));
    }

    public function update(Request $request, Sprint $sprint)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $sprint->update([
            'name' => $request->name,
            'start_date' => \Carbon\Carbon::parse($request->start_date)->format('Y-m-d'),
            'end_date' => \Carbon\Carbon::parse($request->end_date)->format('Y-m-d'),
        ]);

        return redirect()->route('projects.show', $sprint->project_id);
    }

    public function destroy(Sprint $sprint)
    {
        $sprint->delete();
        return redirect()->route('projects.show', $sprint->project_id);
    }

}
