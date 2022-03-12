<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>CAS - Sainte-Johanna</title>
    
        <link rel="stylesheet" type="text/css" href="/vendor/cas-server/reset.css">
        <link rel="stylesheet prefetch" type="text/css" href="//fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900|RobotoDraft:400,100,300,500,700,900">
        <link rel="stylesheet prefetch" type="text/css" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="/vendor/cas-server/casserver.css">
        <link rel="icon" type="image/png" href="https://sainte-johanna.eu.org/wp-content/uploads/2021/12/logo-transparent.png" sizes="192x192">
        <link rel="icon" type="image/png" href="https://sainte-johanna.eu.org/wp-content/uploads/2021/12/logo-transparent-60x60.png" sizes="32x32">
        <link rel="apple-touch-icon" type="image/png" href="https://sainte-johanna.eu.org/wp-content/uploads/2021/12/logo-transparent.png">
        <meta name="msapplication-TileImage" content="https://sainte-johanna.eu.org/wp-content/uploads/2021/12/logo-transparent.png">
    </head>
    <body style="
        background: url('https://sainte-johanna.eu.org/wp-content/uploads/2021/12/college-screenshot.png');
        background-position-x: center;
        background-repeat: no-repeat;
        background-size: cover;
    ">
        <div class="pen-title">
            <img src="https://sainte-johanna.eu.org/wp-content/uploads/2021/12/logo-transparent.png">
        </div>

        <div class="container">
            <div class="card"></div>
            @yield("content")
        </div>
    </body>
</html>