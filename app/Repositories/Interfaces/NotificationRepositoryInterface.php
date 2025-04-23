<?php

namespace App\Repositories\Interfaces;

interface NotificationRepositoryInterface extends RepositoryInterface
{
    /**
     * Get notifications by user
     *
     * @param int $userId
     * @return mixed
     */
    public function getNotificationsByUser($userId);

    /**
     * Get unread notifications by user
     *
     * @param int $userId
     * @return mixed
     */
    public function getUnreadNotifications($userId);

    /**
     * Mark notification as read
     *
     * @param int $notificationId
     * @return mixed
     */
    public function markAsRead($notificationId);

    /**
     * Mark all notifications as read for user
     *
     * @param int $userId
     * @return mixed
     */
    public function markAllAsRead($userId);

    /**
     * Create notification for user
     *
     * @param int $userId
     * @param string $type
     * @param string $message
     * @return mixed
     */
    public function createNotification($userId, $type, $message);
}
