<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>CAS - Your website name</title>
    
        <link rel="stylesheet" type="text/css" href="/vendor/cas-server/reset.css">
        <link rel="stylesheet prefetch" type="text/css" href="//fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900|RobotoDraft:400,100,300,500,700,900">
        <link rel="stylesheet prefetch" type="text/css" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="/vendor/cas-server/cas-server.css">
        <link rel="icon" type="image/png" href="/vendor/cas-server/logo.png">
        <link rel="apple-touch-icon" type="image/png" href="/vendor/cas-server/logo.png">
        <meta name="msapplication-TileImage" content="/vendor/cas-server/logo.png">
    </head>
    <body style="
        background: url('/vendor/cas-server/background.png');
        background-position-x: center;
        background-repeat: no-repeat;
        background-size: cover;
    ">
        <div class="pen-title">
            <img src="/vendor/cas-server/logo.png">
        </div>

        <div class="container">
            <div class="card"></div>
            @yield("content")
        </div>
    </body>
</html>
