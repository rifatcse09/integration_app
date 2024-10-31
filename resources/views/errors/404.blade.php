<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Not Found</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }

        .container {
            text-align: center;
            max-width: 600px;
            padding: 20px;
            background: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .container h1 {
            font-size: 100px;
            margin: 0;
            color: #ff6b6b;
        }

        .container h2 {
            font-size: 24px;
            margin: 10px 0;
        }

        .container p {
            font-size: 18px;
            color: #666;
        }

        .container a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 18px;
            text-decoration: none;
            color: #fff;
            background-color: #ff6b6b;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .container a:hover {
            background-color: #ff4c4c;
        }

        @media (max-width: 600px) {
            .container h1 {
                font-size: 70px;
            }

            .container h2 {
                font-size: 20px;
            }

            .container p {
                font-size: 16px;
            }

            .container a {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>404</h1>
        <h2>Page Not Found</h2>
        <p>Sorry, the page you are looking for does not exist.</p>
    </div>
</body>
</html>
