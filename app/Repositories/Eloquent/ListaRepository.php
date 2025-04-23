<?php

namespace App\Repositories\Eloquent;

use App\Models\Lista;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\ListaRepositoryInterface;

class ListaRepository extends BaseRepository implements ListaRepositoryInterface
{
    /**
     * ListaRepository constructor.
     *
     * @param Lista $model
     */
    public function __construct(Lista $model)
    {
        parent::__construct($model);
    }

    /**
     * Get lists by project
     *
     * @param int $projectId
     * @return mixed
     */
    public function getListsByProject($projectId)
    {
        return $this->model->whereHas('projects', function ($query) use ($projectId) {
            $query->where('projects.project_id', $projectId);
        })->get();
    }

    /**
     * Add list to project
     *
     * @param int $listId
     * @param int $projectId
     * @return mixed
     */
    public function addListToProject($listId, $projectId)
    {
        $lista = $this->find($listId);
        return $lista->projects()->attach($projectId);
    }

    /**
     * Remove list from project
     *
     * @param int $listId
     * @param int $projectId
     * @return mixed
     */
    public function removeListFromProject($listId, $projectId)
    {
        $lista = $this->find($listId);
        return $lista->projects()->detach($projectId);
    }
}
