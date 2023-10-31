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
function getMatchesSourceWilliamhill($url, $bookmaker = 'williamhill', $sport = null, $league = null)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://ws.sportsbook.williamhill.lv/component/datatree');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    $url = '/en_gb/competition/' . "$url";

    $postfields = [
        'context' => [
            'url_key' => $url,
            'clientIp' => '188.92.78.91',
            'version' => '1.0.1',
            'device' => 'web_vuejs_mobile',
            'lang' => 'en_gb',
            'timezone' => 'UTC',
            'url_params' => [],
        ],
    ];

    $postfields_json = json_encode($postfields);

    curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields_json);

    $response = curl_exec($ch);

    curl_close($ch);

    $data = json_decode($response);

    $sport_title = $sport;

    try {
        $existingSports = Sport::all();
        $sport = findOrCreateItemSport($existingSports, $sport ?? $sport_title, Sport::class, 52);

        $existingLeagues = $sport->leagues;
        $league = findOrCreateItemLeague($existingLeagues, $league ?? $league_title, League::class, 52, $sport->id);

        foreach ($data->tree->components as $component) {
            if ($component->tree_compo_key == 'main_content_competition') {
                foreach ($component->components as $component) {
                    if ($component->tree_compo_key == 'prematch_event_list') {
                        foreach ($component->data->events as $event) {
                            $team1 = $event->actors[0]->label;
                            $team2 = $event->actors[1]->label;

                            $existingGames = $league->games;
                            $game = findOrCreateItemGame($existingGames, $team1, $team2, $date ?? now(), Game::class, 52, $league->id);

                            $id = $event->id;
                            $url_match = "https://www.williamhill.lv/en/sports/event/$id";

                            if ($sport_title == 'football') {
                                foreach ($event->markets as $bet) {
                                    if ($bet->selection_order_type == '1x2') {
                                        $odd_team1 = $bet->bets[0]->selections[0]->odds;
                                        $draw = $bet->bets[0]->selections[1]->odds;
                                        $odd_team2 = $bet->bets[0]->selections[2]->odds;

                                        if (!$game['reverse']) {
                                            Odd::updateOrCreate(['game_id' => $game['item']->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team1, 'draw' => $draw, 'odd_team2' => $odd_team2, 'url' => $url_match]);
                                        } else {
                                            Odd::updateOrCreate(['game_id' => $game['item']->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team2, 'draw' => $draw, 'odd_team2' => $odd_team1, 'url' => $url_match]);
                                        }

                                        // echo "$team1:$odd_team1 vs $team2:$odd_team2\n";
                                    }
                                }
                            } else {
                                foreach ($event->markets as $bet) {
                                    if ($bet->selection_order_type == 'moneyline') {
                                        $odd_team1 = $bet->bets[0]->selections[0]->odds;
                                        $draw = 0;
                                        $odd_team2 = $bet->bets[0]->selections[1]->odds;

                                        if (!$game['reverse']) {
                                            Odd::updateOrCreate(['game_id' => $game['item']->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team1, 'draw' => $draw, 'odd_team2' => $odd_team2, 'url' => $url_match]);
                                        } else {
                                            Odd::updateOrCreate(['game_id' => $game['item']->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team2, 'draw' => $draw, 'odd_team2' => $odd_team1, 'url' => $url_match]);
                                        }

                                        // echo "$team1:$odd_team1 vs $team2:$odd_team2\n";
                                    }
                                }
                            }
                        }
                        break;
                    }
                }
                break;
            }
        }

        echo 'williamhill';
    } catch (Exception $e) {
        // Обработка ошибки
        echo "Произошла ошибка: williamhill - $url" . $e->getMessage();
        // Или можете просто проигнорировать ошибку и продолжить выполнение кода дальше
    }
}
