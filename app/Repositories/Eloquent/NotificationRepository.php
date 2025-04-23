<?php

namespace App\Repositories\Eloquent;

use App\Models\Notification;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\NotificationRepositoryInterface;
use Carbon\Carbon;

class NotificationRepository extends BaseRepository implements NotificationRepositoryInterface
{
    /**
     * NotificationRepository constructor.
     *
     * @param Notification $model
     */
    public function __construct(Notification $model)
    {
        parent::__construct($model);
    }

    /**
     * Get notifications by user
     *
     * @param int $userId
     * @return mixed
     */
    public function getNotificationsByUser($userId)
    {
        return $this->model->where('user_id', $userId)->orderBy('created_at', 'desc')->get();
    }

    /**
     * Get unread notifications by user
     *
     * @param int $userId
     * @return mixed
     */
    public function getUnreadNotifications($userId)
    {
        return $this->model->where('user_id', $userId)
            ->whereNull('read_at')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Mark notification as read
     *
     * @param int $notificationId
     * @return mixed
     */
    public function markAsRead($notificationId)
    {
        $notification = $this->find($notificationId);
        $notification->read_at = Carbon::now();
        return $notification->save();
    }

    /**
     * Mark all notifications as read for user
     *
     * @param int $userId
     * @return mixed
     */
    public function markAllAsRead($userId)
    {
        return $this->model->where('user_id', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => Carbon::now()]);
    }

    /**
     * Create notification for user
     *
     * @param int $userId
     * @param string $type
     * @param string $message
     * @return mixed
     */
    public function createNotification($userId, $type, $message)
    {
        return $this->create([
            'user_id' => $userId,
            'type' => $type,
            'message' => $message
        ]);
    }
}
