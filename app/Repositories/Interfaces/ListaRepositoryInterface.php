<?php

namespace App\Repositories\Interfaces;

interface ListaRepositoryInterface extends RepositoryInterface
{
    /**
     * Get lists by project
     *
     * @param int $projectId
     * @return mixed
     */
    public function getListsByProject($projectId);

    /**
     * Add list to project
     *
     * @param int $listId
     * @param int $projectId
     * @return mixed
     */
    public function addListToProject($listId, $projectId);

    /**
     * Remove list from project
     *
     * @param int $listId
     * @param int $projectId
     * @return mixed
     */
    public function removeListFromProject($listId, $projectId);
}
