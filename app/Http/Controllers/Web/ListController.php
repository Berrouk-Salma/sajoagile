<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ListaRepositoryInterface;
use App\Repositories\Interfaces\ProjectRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ListController extends Controller
{
    protected $listaRepository;
    protected $projectRepository;

    /**
     * ListController constructor.
     *
     * @param ListaRepositoryInterface $listaRepository
     * @param ProjectRepositoryInterface $projectRepository
     */
    public function __construct(
        ListaRepositoryInterface $listaRepository,
        ProjectRepositoryInterface $projectRepository
    ) {
        $this->listaRepository = $listaRepository;
        $this->projectRepository = $projectRepository;
    }

    /**
     * Display a listing of the lists for a project.
     *
     * @param  int  $projectId
     * @return \Illuminate\View\View
     */
    public function index($projectId)
    {
        try {
            $project = $this->projectRepository->find($projectId);
            $lists = $this->listaRepository->getListsByProject($projectId);
            
            return view('lists.index', compact('project', 'lists'));
        } catch (\Exception $e) {
            return redirect()->route('projects.index')
                ->with('error', 'Project not found.');
        }
    }

    /**
     * Show the form for creating a new list.
     *
     * @param  int  $projectId
     * @return \Illuminate\View\View
     */
    public function create($projectId)
    {
        try {
            $project = $this->projectRepository->find($projectId);
            return view('lists.create', compact('project'));
        } catch (\Exception $e) {
            return redirect()->route('projects.index')
                ->with('error', 'Project not found.');
        }
    }

    /**
     * Store a newly created list in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $projectId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $projectId)
    {
        try {
            $project = $this->projectRepository->find($projectId);
        } catch (\Exception $e) {
            return redirect()->route('projects.index')
                ->with('error', 'Project not found.');
        }

        $validator = Validator::make($request->all(), [
            'nid' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $list = $this->listaRepository->create([
            'nid' => $request->nid,
            'description' => $request->description,
        ]);

        // Add the list to the project
        $this->listaRepository->addListToProject($list->id_list, $projectId);

        return redirect()->route('lists.index', $projectId)
            ->with('success', 'List created successfully.');
    }

    /**
     * Display the specified list.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        try {
            $list = $this->listaRepository->find($id);
            $projects = $list->projects;
            
            return view('lists.show', compact('list', 'projects'));
        } catch (\Exception $e) {
            return redirect()->route('projects.index')
                ->with('error', 'List not found.');
        }
    }

    /**
     * Show the form for editing the specified list.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        try {
            $list = $this->listaRepository->find($id);
            return view('lists.edit', compact('list'));
        } catch (\Exception $e) {
            return redirect()->route('projects.index')
                ->with('error', 'List not found.');
        }
    }

    /**
     * Update the specified list in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $list = $this->listaRepository->find($id);
        } catch (\Exception $e) {
            return redirect()->route('projects.index')
                ->with('error', 'List not found.');
        }

        $validator = Validator::make($request->all(), [
            'nid' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $this->listaRepository->update([
            'nid' => $request->nid,
            'description' => $request->description,
        ], $id);

        return redirect()->route('lists.show', $id)
            ->with('success', 'List updated successfully.');
    }

    /**
     * Remove the specified list from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $list = $this->listaRepository->find($id);
            
            // Get a project to redirect to
            $projectId = $list->projects->first()->project_id ?? null;
            
            $this->listaRepository->delete($id);
            
            if ($projectId) {
                return redirect()->route('lists.index', $projectId)
                    ->with('success', 'List deleted successfully.');
            } else {
                return redirect()->route('projects.index')
                    ->with('success', 'List deleted successfully.');
            }
        } catch (\Exception $e) {
            return redirect()->route('projects.index')
                ->with('error', 'Error deleting list.');
        }
    }
}
