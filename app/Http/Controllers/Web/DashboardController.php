<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ProjectRepositoryInterface;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use App\Repositories\Interfaces\TeamRepositoryInterface;
use App\Repositories\Interfaces\NotificationRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $projectRepository;
    protected $taskRepository;
    protected $teamRepository;
    protected $notificationRepository;

    /**
     * DashboardController constructor.
     *
     * @param ProjectRepositoryInterface $projectRepository
     * @param TaskRepositoryInterface $taskRepository
     * @param TeamRepositoryInterface $teamRepository
     * @param NotificationRepositoryInterface $notificationRepository
     */
    public function __construct(
        ProjectRepositoryInterface $projectRepository,
        TaskRepositoryInterface $taskRepository,
        TeamRepositoryInterface $teamRepository,
        NotificationRepositoryInterface $notificationRepository
    ) {
        $this->projectRepository = $projectRepository;
        $this->taskRepository = $taskRepository;
        $this->teamRepository = $teamRepository;
        $this->notificationRepository = $notificationRepository;
    }

    /**
     * Display the dashboard page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $userId = Auth::id();
        
        // Get user's projects
        $projects = $this->projectRepository->getProjectsByUser($userId);
        
        // Get user's tasks
        $tasks = $this->taskRepository->getTasksByUser($userId);
        
        // Get user's teams
        $teams = $this->teamRepository->getTeamsByUser($userId);
        
        // Get user's unread notifications
        $notifications = $this->notificationRepository->getUnreadNotifications($userId);

        // Task statistics
        $taskStatistics = [
            'todo' => $tasks->where('status', 'todo')->count(),
            'in_progress' => $tasks->where('status', 'in_progress')->count(),
            'review' => $tasks->where('status', 'review')->count(),
            'done' => $tasks->where('status', 'done')->count(),
        ];

        return view('dashboard', compact(
            'projects',
            'tasks',
            'teams',
            'notifications',
            'taskStatistics'
        ));
    }
}
