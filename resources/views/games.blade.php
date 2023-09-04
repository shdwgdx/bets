<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    @foreach ($games as $game)
        <ul>
            <li> <a href="{{ route('odds-page', ['game' => $game->id, 'league' => $league]) }}">{{ $game->team1 }} VS
                    {{ $game->team2 }}</a></li>
        </ul>
    @endforeach
</body>

</html>
