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
function getMatchesSourceMrgreenCsgo($url, $bookmaker = 'mrgreen')
{
    try {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://ws.sportsbook.mrgreen.lv/component/datatree');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');

        $postfields = [
            'context' => [
                'url_key' => '/en_gb/category/2120-cs-go',
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
        $existingSports = Sport::all();
        $sport = findOrCreateItemSport($existingSports, 'cs', Sport::class, 52);

        foreach ($data->tree->components as $component) {
            if ($component->tree_compo_key == 'main_content_category') {
                foreach ($component->components as $component) {
                    if ($component->tree_compo_key == 'last_minutes_sportcatcomp') {
                        foreach ($component->data->events as $event) {
                            $team1 = $event->actors[0]->label;
                            $team2 = $event->actors[1]->label;
                            $start_date = $event->start;
                            $id = $event->id;
                            $url_match = "https://www.mrgreen.lv/en/sports/event/$id";

                            $league_title = trim(strtolower($event->competition->label));

                            // echo "$league_title\n";

                            $existingLeagues = League::where('sport_id', $sport->id)->get();
                            $league = findOrCreateItemLeagueCsgo($existingLeagues, $league_title, League::class, 52, $sport->id);

                            $existingGames = $league->games;
                            $game = findOrCreateItemGame($existingGames, $team1, $team2, $date ?? now(), Game::class, 52, $league->id, $score_team1 ?? 0, $score_team2 ?? 0, $live ?? 0, $start_date ?? null);

                            foreach ($event->markets as $bet) {
                                if ($bet->selection_order_type == 'moneyline' && $bet->bets[0]->label == 'Match result') {
                                    $odd_team1 = $bet->bets[0]->selections[0]->odds;
                                    $draw = 0;
                                    $odd_team2 = $bet->bets[0]->selections[1]->odds;
                                }
                            }
                            if (!$game['reverse']) {
                                Odd::updateOrCreate(['game_id' => $game['item']->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team1, 'draw' => $draw, 'odd_team2' => $odd_team2, 'url' => $url_match]);
                            } else {
                                Odd::updateOrCreate(['game_id' => $game['item']->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team2, 'draw' => $draw, 'odd_team2' => $odd_team1, 'url' => $url_match]);
                            }
                            // echo "$team1:$odd_team1 vs $team2:$odd_team2\n";
                        }
                        break;
                    }
                }
                break;
            }
        }

        echo 'mrgreen';
    } catch (Exception $e) {
        // Обработка ошибки
        echo "Произошла ошибка: mrgreen - $url" . $e->getMessage();
        // Или можете просто проигнорировать ошибку и продолжить выполнение кода дальше
    }
}
