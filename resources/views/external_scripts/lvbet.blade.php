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
function getMatchesSourceLvbet($url, $bookmaker = 'lvbet', $sport = null, $league = null, $url_string = null)
{
    // Выводим полученные данные
    // var_dump($data);
    try {
        // Используем функцию file_get_contents() для выполнения GET-запроса и получения JSON данных
        $jsonData = file_get_contents($url);

        // Парсим полученные данные в формате JSON
        $data = json_decode($jsonData);
        $sport_title = $sport;
        $existingSports = Sport::all();
        $sport = findOrCreateItemSport($existingSports, $sport ?? $sport_title, Sport::class, 52);

        $existingLeagues = $sport->leagues;
        $league = findOrCreateItemLeague($existingLeagues, $league ?? $league_title, League::class, 52, $sport->id);
        // $matches = $data->Result->Items[0]->Events;
        foreach ($data->primary_column_markets as $market) {
            if ($market->name == 'Match Result' && $sport_title == 'football') {
                $team1 = $market->selections[0]->name;
                $team2 = $market->selections[2]->name;
                $existingGames = $league->games;
                $game = findOrCreateItemGame($existingGames, $team1, $team2, $date ?? now(), Game::class, 52, $league->id);
                $match_id = $market->match_id;
                $teams_string = str_replace(' ', '-', strtolower($team1)) . '-vs-' . str_replace(' ', '-', strtolower($team2));
                $url_match = str_replace(['teams_string', 'match_id'], [$teams_string, $match_id], $url_string);
                if (count($market->selections) == 3) {
                    $odd_team1 = $market->selections[0]->rate->decimal;
                    $draw = $market->selections[1]->rate->decimal;
                    $odd_team2 = $market->selections[2]->rate->decimal;
                    if (!$game['reverse']) {
                        Odd::updateOrCreate(['game_id' => $game['item']->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team1, 'draw' => $draw, 'odd_team2' => $odd_team2, 'url' => $url_match]);
                    } else {
                        Odd::updateOrCreate(['game_id' => $game['item']->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team2, 'draw' => $draw, 'odd_team2' => $odd_team1, 'url' => $url_match]);
                    }
                }
            } elseif (($market->name == 'Money Line' || $market->name == 'Match Winner') && $sport_title !== 'football') {
                $team1 = $market->selections[0]->name;
                $team2 = $market->selections[1]->name;
                $existingGames = $league->games;
                $game = findOrCreateItemGame($existingGames, $team1, $team2, $date ?? now(), Game::class, 52, $league->id);
                $match_id = $market->match_id;
                $teams_string = str_replace(' ', '-', strtolower($team1)) . '-vs-' . str_replace(' ', '-', strtolower($team2));
                $url_match = str_replace(['teams_string', 'match_id'], [$teams_string, $match_id], $url_string);

                $odd_team1 = $market->selections[0]->rate->decimal;
                $draw = 0;
                $odd_team2 = $market->selections[1]->rate->decimal;
                if (!$game['reverse']) {
                    Odd::updateOrCreate(['game_id' => $game['item']->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team1, 'draw' => $draw, 'odd_team2' => $odd_team2, 'url' => $url_match]);
                } else {
                    Odd::updateOrCreate(['game_id' => $game['item']->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team2, 'draw' => $draw, 'odd_team2' => $odd_team1, 'url' => $url_match]);
                }
            }
        }
        echo 'lvbet';
    } catch (Exception $e) {
        // Обработка ошибки
        echo "Произошла ошибка: lvbet - $url" . $e->getMessage();
        // Или можете просто проигнорировать ошибку и продолжить выполнение кода дальше
    }
}
