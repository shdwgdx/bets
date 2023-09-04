<?php

function getMatchesSource($url = 'https://sb-data.klondaika.lv/en/events/group/476/?domainId=17&gameTypes=1,3,9,10,15,18,21,16,19,22,11,12,24,2,108,112,59,34,37,40,43,35,38,41,44,95,4,109&lsp=eyJ1cmwiOiJodHRwczovL3ZzdHJlYW1lci5lbmxhYnMuc2VydmljZXMiLCJwcm92aWRlcl9pZHMiOltdfQ==', $bookmaker = 'pafbet')
{
    $opts = array(
        'http' => array(
            'user_agent' => 'Y	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.36 Edg/116.0.1938.54', // Имя пользователя для запроса
            'header' => 'Content-type: application/json', // Заголовки запроса
        )
    );

    $context = stream_context_create($opts);

    $jsonData = file_get_contents($url, false, $context);


    // Парсим полученные данные в формате JSON
    $data = json_decode($jsonData);
    echo count($data);
    // Выводим полученные данные
    foreach ($data as $match) {
        $team1 = $match->player1->name;
        $team2 = $match->player1->name;
        $odd_team1 = $match->games[0]->odds[0]->value;
        $draw = $match->games[0]->odds[1]->value;
        $odd_team2 = $match->games[0]->odds[2]->value;
        echo "$odd_team1 \t\t";
        echo "$draw \t\t";
        echo "$odd_team2 \t\t\n";
    }

    // if (isset($data->events)) {
    //     foreach ($data->events as $event) {
    //         $team1 = $event->event->homeName;
    //         $team2 = $event->event->awayName;
    //         // echo $team1;
    //         // echo $team2;
    //         if (isset($event->betOffers)) {
    //             if ($event->betOffers[0]->outcomes == 3) {
    //                 $odd_team1 = $event->betOffers[0]->outcomes[0]->odds / 1000;
    //                 $draw = $event->betOffers[0]->outcomes[1]->odds / 1000;
    //                 $odd_team2 = $event->betOffers[0]->outcomes[2]->odds / 1000;
    //             } else {
    //                 $odd_team1 = $event->betOffers[0]->outcomes[0]->odds / 1000;
    //                 $odd_team2 = $event->betOffers[0]->outcomes[1]->odds / 1000;
    //                 $draw = 0;
    //             }
    //         }
    //     }
    // }
}
getMatchesSource();
