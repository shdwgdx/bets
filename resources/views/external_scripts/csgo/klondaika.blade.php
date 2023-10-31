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
function getMatchesSourceKlondaikaCsgo($url, $bookmaker = 'klondaika', $sport = 'csgo')
{
    $opts = [
        'http' => [
            'user_agent' => 'Y	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.36 Edg/116.0.1938.54', // Имя пользователя для запроса
            'header' => 'Content-type: application/json', // Заголовки запроса
        ],
    ];

    $context = stream_context_create($opts);

    $jsonData = file_get_contents($url, false, $context);

    // Парсим полученные данные в формате JSON
    $data = json_decode($jsonData);

    // Выводим полученные данные
    // var_dump($data);
    try {
        $existingSports = Sport::all();
        $sport = findOrCreateItemSport($existingSports, 'csgo', Sport::class, 52);

        if (isset($data)) {
            foreach ($data as $match) {
                if (strtolower(trim($match->parentGroups[1]->name)) == 'cs:go') {
                    if (isset($match->player1->name)) {
                        $id = $match->id;
                        $url_match = "https://klondaika.lv/en/sport/prematch/event/$id";
                        $league_title = strtolower(trim($match->parentGroups[2]->name));
                        // var_dump($match->parentGroups[2]);
                        $existingLeagues = League::where('sport_id', $sport->id)->get();
                        $league = findOrCreateItemLeagueCsgo($existingLeagues, $league_title, League::class, 52, $sport->id);
                        $team1 = $match->player1->name;
                        $team2 = $match->player2->name;
                        $existingGames = $league->games;

                        $game = findOrCreateItemGameCsgo($existingGames, $team1, $team2, $date ?? now(), Game::class, 52, $league->id);

                        if (count($match->games[0]->odds) == 3) {
                            $odd_team1 = $match->games[0]->odds[0]->value;
                            $draw = $match->games[0]->odds[1]->value;
                            $odd_team2 = $match->games[0]->odds[2]->value;
                            if (!$game['reverse']) {
                                Odd::updateOrCreate(['game_id' => $game['item']->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team1, 'draw' => $draw, 'odd_team2' => $odd_team2, 'url' => $url_match ?? null]);
                            } else {
                                Odd::updateOrCreate(['game_id' => $game['item']->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team2, 'draw' => $draw, 'odd_team2' => $odd_team1, 'url' => $url_match ?? null]);
                            }
                        } else {
                            $odd_team1 = $match->games[0]->odds[0]->value;
                            $odd_team2 = $match->games[0]->odds[1]->value;
                            $draw = 0;
                            if (!$game['reverse']) {
                                Odd::updateOrCreate(['game_id' => $game['item']->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team1, 'draw' => $draw, 'odd_team2' => $odd_team2, 'url' => $url_match ?? null]);
                            } else {
                                Odd::updateOrCreate(['game_id' => $game['item']->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team2, 'draw' => $draw, 'odd_team2' => $odd_team1, 'url' => $url_match ?? null]);
                            }
                        }
                    }
                }
            }
        }
        echo 'klondaikaCsgo';
    } catch (Exception $e) {
        // Обработка ошибки
        echo "Произошла ошибка: klondaikaCsgo - $url" . $e->getMessage();
        // Или можете просто проигнорировать ошибку и продолжить выполнение кода дальше
    }
}
