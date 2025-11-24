<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>500 - Server Meltdown</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            margin: 0;
            background: rgb(15, 23, 42);
            color: lightgray;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .container {
            text-align: center;
            max-width: 600px;
        }

        .error-code {
            font-size: 30px;
            font-weight: 800;
            color: rgb(239, 68, 68);
            margin: 2px 0 3px;
        }

        .subtitle {
            font-size: 20px;
            margin-bottom: 20px;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            padding: 14px 18px;
            border-radius: 8px;
            background: rgba(0, 0, 255, 0.75);
            color: white;
            text-decoration: none;
            font-weight: 600;
        }

        a:hover {
            background: darkblue;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="error-code">HTTP 500</div>

    <p class="subtitle">Something exploded on our server. It’s not you. It’s us.</p>

    <a href="{{ url('/') }}">Back to safety</a>
</div>
</body>
</html>
