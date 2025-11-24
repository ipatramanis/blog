<?php

namespace App\Notifications;

use App\Mail\NewComment as NewCommentMailable;
use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Notification;

class NewComment extends Notification implements ShouldQueue
{
    use Queueable;

    public Comment $comment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): Mailable
    {
        // Load model relationships
        $comment = $this->comment->loadMissing(['posts.author', 'user']);

        return (new NewCommentMailable($comment))->to($comment->posts->author()->value('email'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        // Load model relationships
        $comment = $this->comment->load(['user', 'posts']);

        return [
            'comment_id' => $comment->id,
            'comment' => $comment,
        ];
    }
}
