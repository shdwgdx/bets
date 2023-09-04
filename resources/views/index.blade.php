<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">

</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                @foreach ($sports as $sport)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="footballDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            {{ $sport->title }}
                        </a>

                        <ul class="dropdown-menu" aria-labelledby="footballDropdown">
                            @foreach ($sport->leagues as $league)
                                @if (count($league->games) > 0)
                                    <li><a class="dropdown-item"
                                            href="{{ route('games-page', ['league' => $league]) }}">{{ $league->title }}</a>
                                    </li>
                                    <!-- Добавьте другие лиги футбола здесь -->
                                @endif
                            @endforeach
                        </ul>

                    </li>
                @endforeach
                <!-- Добавьте другие виды спорта с их лигами здесь -->
            </ul>

        </div>
    </nav>



    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"
        integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa" crossorigin="anonymous">
    </script>
</body>


</html>
