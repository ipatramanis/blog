<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Notifications\NewComment;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Mail\Mailable;
use Throwable;

class CommentController extends Controller
{
    /**
     * Preview comment notification
     *
     * @param int $commentId
     *
     * @return Factory|Mailable|View
     */
    public function previewNotification(int $commentId)
    {
        try {
            $comment = Comment::find($commentId);

            return (new NewComment($comment))->toMail($comment);
        } catch (Throwable $e) {
            logger()->error($e->getMessage());

            return view('errors.500');
        }
    }
}
