<?php

namespace App\Notifications;

use App\Models\Problem;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class NewProblemNotification extends Notification
{
    use Queueable;

    public function __construct(public Problem $problem) {}

    /**
     * ช่องทางการแจ้งเตือน
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * ข้อมูลที่จะบันทึกลงตาราง notifications
     */
    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'New Problem Reported',
            'message' => 'Problem ID ' . $this->problem->prob_id . ' has been reported.',
            'url' => url('/admin/resources/problems/' . $this->problem->id . '/view'),
        ];
    }
}
