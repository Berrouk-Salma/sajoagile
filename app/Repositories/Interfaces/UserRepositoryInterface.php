<?php

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface extends RepositoryInterface
{
    /**
     * Get user by email
     *
     * @param string $email
     * @return mixed
     */
    public function findByEmail($email);

    /**
     * Get users by role
     *
     * @param string $role
     * @return mixed
     */
    public function findByRole($role);

    /**
     * Get users by team
     *
     * @param int $teamId
     * @return mixed
     */
    public function findByTeam($teamId);

    /**
     * Get users by project
     *
     * @param int $projectId
     * @return mixed
     */
    public function findByProject($projectId);

    /**
     * Authenticate user (login)
     *
     * @param array $credentials
     * @return mixed
     */
    public function login(array $credentials);

    /**
     * Update user profile
     *
     * @param array $data
     * @param int $userId
     * @return mixed
     */
    public function updateProfile(array $data, $userId);
}
