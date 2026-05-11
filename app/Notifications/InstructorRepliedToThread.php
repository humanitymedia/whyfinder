<?php

namespace App\Notifications;

use App\Models\ForumReply;
use App\Models\ForumThread;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class InstructorRepliedToThread extends Notification
{
    use Queueable;

    public function __construct(
        public ForumThread $thread,
        public ForumReply $reply,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'thread_id' => $this->thread->id,
            'thread_title' => $this->thread->title,
            'thread_slug' => $this->thread->slug,
            'course_slug' => $this->thread->course->slug,
            'course_title' => $this->thread->course->title,
            'reply_id' => $this->reply->id,
            'replier_name' => $this->reply->user->name,
            'message' => $this->reply->user->name . ' replied to your thread "' . $this->thread->title . '"',
        ];
    }
}
