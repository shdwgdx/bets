<?php
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverWait;
use Facebook\WebDriver\WebDriverExpectedCondition;

use Carbon\Carbon;
use App\Models\Game;
use App\Models\League;
use App\Models\Odd;
use App\Models\Sport;
function getMatchesSourceLvbetCsgo($url, $bookmaker = 'lvbet')
{
    try {
        $leagues = 'https://offer.lvbet.lv/client-api/v4/sports-groups/?lang=en&country=es&sports_groups_ids=51,7453,7612,7881,7955,63579,72264,72302,72366,72833,73053,73915,75293,75307,76787';
        $matches = 'https://offer.lvbet.lv/client-api/v4/matches/competition-view/?sports_groups_ids=7453';

        // Используем функцию file_get_contents() для выполнения GET-запроса и получения JSON данных
        $jsonLeagues = file_get_contents($leagues);
        // Парсим полученные данные в формате JSON
        $leagues = json_decode($jsonLeagues);

        // Фильтрация массива по sports_group_id
        $result = array_filter($leagues, function ($item) {
            return $item->sports_group_id != 51 && $item->sports_group_id != 7453;
        });

        // Создание массива объектов [{name, sports_group_id}]
        $leaguesArray = array_map(function ($item) {
            return [
                'name' => $item->name,
                'sports_group_id' => $item->sports_group_id,
            ];
        }, $result);

        // print_r($leaguesArray);

        // Используем функцию file_get_contents() для выполнения GET-запроса и получения JSON данных
        $jsonMatches = file_get_contents($matches);
        // Парсим полученные данные в формате JSON
        $matches = json_decode($jsonMatches);

        $existingSports = Sport::all();
        $sport = findOrCreateItemSport($existingSports, 'csgo', Sport::class, 52);

        foreach ($leaguesArray as $leagueItem) {
            $league_title = strtolower($leagueItem['name']);
            $league_string = str_replace(' ', '-', strtolower($league_title));
            $url_startmatch = 'https://lvbet.lv/sports/en/pre-matches/counter-strike:-go-cs:go/world/' . $league_string . '/';
            $existingLeagues = $sport->leagues;
            $league = findOrCreateItemLeagueCsgo($existingLeagues, $league_title, League::class, 52, $sport->id);

            foreach ($matches->matches as $market) {
                if ($market->sports_groups_ids[2] == $leagueItem['sports_group_id']) {
                    $match_id = $market->match_id;
                    foreach ($matches->primary_column_markets as $market) {
                        if ($market->label == 'Match Winner' && $market->match_id == $match_id) {
                            $team1 = $market->selections[0]->name;
                            $team2 = $market->selections[1]->name;
                            $url_match = $url_startmatch . str_replace(' ', '-', strtolower($team1)) . '-vs-' . str_replace(' ', '-', strtolower($team2)) . "/--/51/7453/{$leagueItem['sports_group_id']}/$match_id";
                            // echo "$team1 - $team2 - $match_id - {$leagueItem['name']}  \n";

                            // $teams_string = str_replace(' ', '-', strtolower($team1)) . '-vs-' . str_replace(' ', '-', strtolower($team2));
                            // $url_match = str_replace(['teams_string', 'match_id'], [$teams_string, $match_id], $url_string);

                            $odd_team1 = $market->selections[0]->rate->decimal;
                            $draw = 0;
                            $odd_team2 = $market->selections[1]->rate->decimal;

                            $existingGames = $league->games;
                            $game = findOrCreateItemGame($existingGames, $team1, $team2, $date ?? now(), Game::class, 52, $league->id);
                            if (!$game['reverse']) {
                                Odd::updateOrCreate(['game_id' => $game['item']->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team1, 'draw' => $draw, 'odd_team2' => $odd_team2, 'url' => $url_match]);
                            } else {
                                Odd::updateOrCreate(['game_id' => $game['item']->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team2, 'draw' => $draw, 'odd_team2' => $odd_team1, 'url' => $url_match]);
                            }
                            break;
                        }
                    }
                }
            }
        }

        // foreach ($matches->primary_column_markets as $market) {
        //     if ($market->label == 'Match Winner') {
        //         $team1 = $market->selections[0]->name;
        //         $team2 = $market->selections[1]->name;

        //         $match_id = $market->match_id;
        //         echo "$team1 - $team2 - $match_id\n";

        //         // $existingGames = $leagueItem->games;
        //         // $game = findOrCreateItemGame($existingGames, $team1, $team2, $date ?? now(), Game::class, 52, $leagueItem->id);

        //         // $teams_string = str_replace(' ', '-', strtolower($team1)) . '-vs-' . str_replace(' ', '-', strtolower($team2));
        //         // $url_match = str_replace(['teams_string', 'match_id'], [$teams_string, $match_id], $url_string);
        //         // if (count($market->selections) == 3) {
        //         //     $odd_team1 = $market->selections[0]->rate->decimal;
        //         //     $draw = $market->selections[1]->rate->decimal;
        //         //     $odd_team2 = $market->selections[2]->rate->decimal;
        //         //     if (!$game['reverse']) {
        //         //         Odd::updateOrCreate(['game_id' => $game['item']->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team1, 'draw' => $draw, 'odd_team2' => $odd_team2, 'url' => $url_match]);
        //         //     } else {
        //         //         Odd::updateOrCreate(['game_id' => $game['item']->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team2, 'draw' => $draw, 'odd_team2' => $odd_team1, 'url' => $url_match]);
        //         //     }
        //         // }
        //     }
        // }

        echo 'lvbetCsgo';
    } catch (Exception $e) {
        // Обработка ошибки
        echo "Произошла ошибка: lvbetCsgo - $url" . $e->getMessage();
        // Или можете просто проигнорировать ошибку и продолжить выполнение кода дальше
    }
}
