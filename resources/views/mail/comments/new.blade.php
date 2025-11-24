<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Comment</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: lightgray;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            padding: 24px;
            background-color: #ffffff;
            border-radius: 8px;
            box-sizing: border-box;
        }

        .title {
            margin: 0 0 16px 0;
            font-size: 22px;
            font-weight: 600;
            text-align: left;
        }

        .content-wrapper {
            padding: 16px 18px;
            background-color: rgba(255, 182, 193, 0.09);
            border-radius: 6px;
            border: 1px solid lightgray;
        }

        .post-wrapper {
            margin-bottom: 10px;
            font-size: 15px;
            padding-bottom: 8px;
            border-bottom: 1px solid lightgray;
        }

        .comment-content {
            margin-top: 10px;
            font-size: 15px;
            color: grey;
        }

        .comment-by {
            margin-top: 12px;
            font-size: 13px;
            color: grey;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="title">New comment published on your post!</h2>
        <div class="content-wrapper">
            <p class="post-wrapper">PostID:{{ $comment->post_id }} | Post Title: {{ $comment->posts->title }}</p>
            <p class="comment-content">Comment: {{ $comment->content }}</p>
            <p class="comment-by">Comment by: {{ $comment->user->name }}</p>
        </div>
    </div>
</body>
</html>
