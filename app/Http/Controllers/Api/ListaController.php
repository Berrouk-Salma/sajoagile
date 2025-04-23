<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ListaRepositoryInterface;
use App\Repositories\Interfaces\ProjectRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ListaController extends Controller
{
    protected $listaRepository;
    protected $projectRepository;

    /**
     * ListaController constructor.
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
     * Display a listing of the lists.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $lists = $this->listaRepository->all();
        return response()->json($lists);
    }

    /**
     * Store a newly created list in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nid' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $list = $this->listaRepository->create([
            'nid' => $request->nid,
            'description' => $request->description,
        ]);

        return response()->json($list, 201);
    }

    /**
     * Display the specified list.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $list = $this->listaRepository->find($id);
            return response()->json($list);
        } catch (\Exception $e) {
            return response()->json(['message' => 'List not found'], 404);
        }
    }

    /**
     * Update the specified list in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $list = $this->listaRepository->find($id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'List not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nid' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $this->listaRepository->update($request->only([
            'nid', 'description'
        ]), $id);

        $list = $this->listaRepository->find($id);
        return response()->json($list);
    }

    /**
     * Remove the specified list from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $this->listaRepository->delete($id);
            return response()->json(['message' => 'List deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting list'], 500);
        }
    }

    /**
     * Get all lists by project.
     *
     * @param  int  $projectId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getListsByProject($projectId)
    {
        try {
            $project = $this->projectRepository->find($projectId);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        $lists = $this->listaRepository->getListsByProject($projectId);
        return response()->json($lists);
    }

    /**
     * Add a list to a project.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $projectId
     * @return \Illuminate\Http\JsonResponse
     */
    public function addListToProject(Request $request, $projectId)
    {
        try {
            $project = $this->projectRepository->find($projectId);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'list_id' => 'required|exists:lists,id_list',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $this->listaRepository->addListToProject($request->list_id, $projectId);

        return response()->json(['message' => 'List added to project successfully']);
    }

    /**
     * Remove a list from a project.
     *
     * @param  int  $projectId
     * @param  int  $listId
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeListFromProject($projectId, $listId)
    {
        try {
            $project = $this->projectRepository->find($projectId);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        try {
            $list = $this->listaRepository->find($listId);
        } catch (\Exception $e) {
            return response()->json(['message' => 'List not found'], 404);
        }

        $this->listaRepository->removeListFromProject($listId, $projectId);

        return response()->json(['message' => 'List removed from project successfully']);
    }
}
