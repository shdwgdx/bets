<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <style>
        /* Стили таблицы (IKSWEB) */
        table.iksweb {
            text-decoration: none;
            border-collapse: collapse;
            width: 100%;
            text-align: center;
        }

        table.iksweb th {
            font-weight: normal;
            font-size: 24px;
            color: #9e1b1b;
            background-color: #0f65a3;
        }

        table.iksweb td {
            font-size: 18px;
            color: #2f629d;
        }

        table.iksweb td,
        table.iksweb th {
            white-space: pre-wrap;
            padding: 10px 5px;
            line-height: 13px;
            vertical-align: middle;
            border: 2px solid #0f65a3;
        }

        table.iksweb tr:hover {
            background-color: #f9fafb
        }

        table.iksweb tr:hover td {
            color: #354251;
            cursor: default;
        }
    </style>
</head>

<body>
    <table class="iksweb" style="margin-bottom: 30px">
        <tbody>
            <tr>
                <td></td>
                <td>{{ $game->team1 }}</td>
                <td>Ничья</td>
                <td>{{ $game->team2 }}</td>
            </tr>
            @foreach ($odds as $odd)
                <tr>
                    <td>{{ $odd->bookmaker_name }}</td>
                    <td>{{ $odd->odd_team1 }}</td>
                    <td>{{ $odd->draw }}</td>
                    <td>{{ $odd->odd_team2 }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"
        integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa" crossorigin="anonymous">
    </script>
</body>


</html>
