<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>AKB-FACE RECOGNITION</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            width: 100vw;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        canvas {
            position: absolute;
        }

    </style>

    <script defer src="{{ asset('/js') }}/face-api.min.js"></script>
    <script defer src="{{ asset('/js') }}/face-recognition.js"></script>
</head>

<body>
    @yield('content')
</body>

</html>
