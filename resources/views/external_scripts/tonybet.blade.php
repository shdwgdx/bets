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
function getMatchesSourceTonybet($url, $bookmaker = 'tonybet', $sport = null, $league = null, $url_string = null)
{
    try {
        // Используем функцию file_get_contents() для выполнения GET-запроса и получения JSON данных
        $jsonData = file_get_contents($url);

        // Парсим полученные данные в формате JSON
        $data = json_decode($jsonData);
        $sport_title = $sport;
        // Выводим полученные данные
        // var_dump($data);
        $existingSports = Sport::all();
        $sport = findOrCreateItemSport($existingSports, $sport ?? $sport_title, Sport::class, 52);

        $existingLeagues = $sport->leagues;
        $league = findOrCreateItemLeague($existingLeagues, $league ?? $league_title, League::class, 52, $sport->id);
        $items = $data->data->items;
        $ids = array_column($items, 'id');
        $competitorIds = array_column($items, 'competitor2Id', 'competitor1Id');
        foreach ($ids as $id) {
            $match_id = $id;
            $url_match = str_replace(['match_id'], [$match_id], $url_string);
            $competitor1Id = null;
            $competitor2Id = null;
            $score_team1 = null;
            $score_team2 = null;
            $live = false;
            // Ищем соответствующий элемент с заданным id
            foreach ($items as $item) {
                if ($item->id == $id) {
                    $competitor1Id = $item->competitor1Id;
                    $competitor2Id = $item->competitor2Id;
                    $start_date = $item->time;
                    break;
                }
            }

            // Ищем соответствующий элемент в массиве competitors и берем его name
            foreach ($data->data->relations->competitors as $competitor) {
                if ($competitor->id == $competitor1Id) {
                    $team1 = $competitor->name;
                }
                if ($competitor->id == $competitor2Id) {
                    $team2 = $competitor->name;
                }
            }
            if ($data->data->relations->result) {
                foreach ($data->data->relations->result as $result) {
                    if ($result->eventId == $match_id) {
                        if ($result->team1Score != null || $result->team2Score != null) {
                            $score_team1 = $result->team1Score;
                            $score_team2 = $result->team2Score;
                            $live = true;
                        }
                    }
                }
            }

            $existingGames = $league->games;
            $game = findOrCreateItemGame($existingGames, $team1, $team2, $date ?? now(), Game::class, 52, $league->id, $score_team1, $score_team2, $live, $start_date);

            if ($sport_title == 'football') {
                foreach ($data->data->relations->odds->{$id} as $outcomes) {
                    if ($outcomes->vendorMarketId == 1 && count($outcomes->outcomes) == 3) {
                        $odd_team1 = $outcomes->outcomes[0]->odds;
                        $draw = $outcomes->outcomes[1]->odds;
                        $odd_team2 = $outcomes->outcomes[2]->odds;

                        if (!$game['reverse']) {
                            Odd::updateOrCreate(['game_id' => $game['item']->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team1, 'draw' => $draw, 'odd_team2' => $odd_team2, 'url' => $url_match ?? null]);
                        } else {
                            Odd::updateOrCreate(['game_id' => $game['item']->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team2, 'draw' => $draw, 'odd_team2' => $odd_team1, 'url' => $url_match ?? null]);
                        }
                        break;
                    }
                }
            } elseif ($sport_title == 'hockey') {
                foreach ($data->data->relations->odds->{$id} as $outcomes) {
                    if ($outcomes->vendorMarketId == 406 && count($outcomes->outcomes) == 2) {
                        $odd_team1 = $outcomes->outcomes[0]->odds;
                        $odd_team2 = $outcomes->outcomes[1]->odds;
                        $draw = 0;

                        if (!$game['reverse']) {
                            Odd::updateOrCreate(['game_id' => $game['item']->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team1, 'draw' => $draw, 'odd_team2' => $odd_team2, 'url' => $url_match ?? null]);
                        } else {
                            Odd::updateOrCreate(['game_id' => $game['item']->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team2, 'draw' => $draw, 'odd_team2' => $odd_team1, 'url' => $url_match ?? null]);
                        }
                        break;
                    }
                }
            } else {
                foreach ($data->data->relations->odds->{$id} as $outcomes) {
                    if ($outcomes->vendorMarketId == 219 && count($outcomes->outcomes) == 2) {
                        $odd_team1 = $outcomes->outcomes[0]->odds;
                        $odd_team2 = $outcomes->outcomes[1]->odds;
                        $draw = 0;

                        if (!$game['reverse']) {
                            Odd::updateOrCreate(['game_id' => $game['item']->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team1, 'draw' => $draw, 'odd_team2' => $odd_team2, 'url' => $url_match ?? null]);
                        } else {
                            Odd::updateOrCreate(['game_id' => $game['item']->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team2, 'draw' => $draw, 'odd_team2' => $odd_team1, 'url' => $url_match ?? null]);
                        }
                        break;
                    }
                }
            }
            // echo "id: $id\n";
            // echo "competitor1Name: $team1\n";
            // echo "competitor2Name: $team2\n";
            // echo " $odd_team1\n";
            // echo "draw: $draw\n";
            // echo " $odd_team2\n";
            // echo "-------------------\n";
        }
        echo 'tonybet';
    } catch (Exception $e) {
        // Обработка ошибки
        echo "Произошла ошибка: tonybet - $url" . $e->getMessage();
        // Или можете просто проигнорировать ошибку и продолжить выполнение кода дальше
    }
}
