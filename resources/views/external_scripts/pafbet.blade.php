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
function getMatchesSourcePafbet($url, $bookmaker = 'pafbet', $sport = null, $league = null)
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
        if (isset($data->events)) {
            foreach ($data->events as $event) {
                if (isset($event->event->homeName) && isset($event->event->awayName)) {
                    $team1 = $event->event->homeName;
                    $team2 = $event->event->awayName;
                    $existingGames = $league->games;
                    $game = findOrCreateItemGame($existingGames, $team1, $team2, $date ?? now(), Game::class, 52, $league->id);
                    if (isset($event->betOffers)) {
                        if (count($event->betOffers[0]->outcomes) == 3) {
                            $odd_team1 = $event->betOffers[0]->outcomes[0]->odds / 1000;
                            $draw = $event->betOffers[0]->outcomes[1]->odds / 1000;
                            $odd_team2 = $event->betOffers[0]->outcomes[2]->odds / 1000;
                            if (!$game['reverse']) {
                                Odd::updateOrCreate(['game_id' => $game['item']->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team1, 'draw' => $draw, 'odd_team2' => $odd_team2]);
                            } else {
                                Odd::updateOrCreate(['game_id' => $game['item']->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team2, 'draw' => $draw, 'odd_team2' => $odd_team1]);
                            }
                        } else {
                            $odd_team1 = $event->betOffers[0]->outcomes[0]->odds / 1000;
                            $odd_team2 = $event->betOffers[0]->outcomes[1]->odds / 1000;
                            $draw = 0;
                            if (!$game['reverse']) {
                                Odd::updateOrCreate(['game_id' => $game['item']->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team1, 'draw' => $draw, 'odd_team2' => $odd_team2]);
                            } else {
                                Odd::updateOrCreate(['game_id' => $game['item']->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team2, 'draw' => $draw, 'odd_team2' => $odd_team1]);
                            }
                        }
                    }
                }
            }
        }
        echo 'pafbet';
    } catch (Exception $e) {
        // Обработка ошибки
        echo "Произошла ошибка: pafbet - $url" . $e->getMessage();
        // Или можете просто проигнорировать ошибку и продолжить выполнение кода дальше
    }
}
