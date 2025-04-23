<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * UserRepository constructor.
     *
     * @param User $model
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * Find user by email
     *
     * @param string $email
     * @return mixed
     */
    public function findByEmail($email)
    {
        return $this->model->where('email', $email)->first();
    }

    /**
     * Find users by role
     *
     * @param string $role
     * @return mixed
     */
    public function findByRole($role)
    {
        return $this->model->where('role', $role)->get();
    }

    /**
     * Find users by team
     *
     * @param int $teamId
     * @return mixed
     */
    public function findByTeam($teamId)
    {
        return $this->model->whereHas('teams', function ($query) use ($teamId) {
            $query->where('teams.team_id', $teamId);
        })->get();
    }

    /**
     * Find users by project
     *
     * @param int $projectId
     * @return mixed
     */
    public function findByProject($projectId)
    {
        return $this->model->whereHas('projects', function ($query) use ($projectId) {
            $query->where('projects.project_id', $projectId);
        })->get();
    }

    /**
     * Authenticate user (login)
     *
     * @param array $credentials
     * @return mixed
     */
    public function login(array $credentials)
    {
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            return $user->createToken('auth_token')->plainTextToken;
        }

        return null;
    }

    /**
     * Update user profile
     *
     * @param array $data
     * @param int $userId
     * @return mixed
     */
    public function updateProfile(array $data, $userId)
    {
        $user = $this->find($userId);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $user->update($data);
    }
}
