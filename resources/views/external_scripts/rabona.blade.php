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
function getMatchesSourceRabona($url, $bookmaker = 'rabona', $sport = null, $league = null)
{
    // Используем функцию file_get_contents() для выполнения GET-запроса и получения JSON данных
    $jsonData = file_get_contents($url);

    // Парсим полученные данные в формате JSON
    $data = json_decode($jsonData);

    // Выводим полученные данные
    // var_dump($data);
    try {
        $existingSports = Sport::all();
        $sport = findOrCreateItemSport($existingSports, $sport ?? $sport_title, Sport::class, 52);

        $existingLeagues = $sport->leagues;
        $league = findOrCreateItemLeague($existingLeagues, $league ?? $league_title, League::class, 52, $sport->id);
        $matches = $data->Result->Items[0]->Events;
        foreach ($matches as $match) {
            $team1 = $match->Competitors[0]->Name;
            $team2 = $match->Competitors[1]->Name;
            $existingGames = $league->games;
            $game = findOrCreateItemGame($existingGames, $team1, $team2, $date ?? now(), Game::class, 52, $league->id);

            $id = $match->Id;
            $url_match = "https://rab0na-3142.com/en/sport?eventid=$id";

            if (count($match->Items[0]->Items) == 3) {
                $odd_team1 = substr($match->Items[0]->Items[0]->Price, 0, strpos($match->Items[0]->Items[0]->Price, '.') + 3);
                $draw = substr($match->Items[0]->Items[1]->Price, 0, strpos($match->Items[0]->Items[0]->Price, '.') + 3);
                $odd_team2 = substr($match->Items[0]->Items[2]->Price, 0, strpos($match->Items[0]->Items[0]->Price, '.') + 3);
                if (!$game['reverse']) {
                    Odd::updateOrCreate(['game_id' => $game['item']->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team1, 'draw' => $draw, 'odd_team2' => $odd_team2, 'url' => $url_match ?? null]);
                } else {
                    Odd::updateOrCreate(['game_id' => $game['item']->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team2, 'draw' => $draw, 'odd_team2' => $odd_team1, 'url' => $url_match ?? null]);
                }
            } else {
                $odd_team1 = substr($match->Items[0]->Items[0]->Price, 0, strpos($match->Items[0]->Items[0]->Price, '.') + 3);
                $odd_team2 = substr($match->Items[0]->Items[1]->Price, 0, strpos($match->Items[0]->Items[0]->Price, '.') + 3);
                $draw = 0;
                if (!$game['reverse']) {
                    Odd::updateOrCreate(['game_id' => $game['item']->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team1, 'draw' => $draw, 'odd_team2' => $odd_team2, 'url' => $url_match ?? null]);
                } else {
                    Odd::updateOrCreate(['game_id' => $game['item']->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team2, 'draw' => $draw, 'odd_team2' => $odd_team1, 'url' => $url_match ?? null]);
                }
            }
        }
        echo 'rabona';
    } catch (Exception $e) {
        // Обработка ошибки
        echo "Произошла ошибка: rabona - $url" . $e->getMessage();
        // Или можете просто проигнорировать ошибку и продолжить выполнение кода дальше
    }
}
