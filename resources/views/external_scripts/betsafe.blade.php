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
function getMatchesSourceBetsafe($url, $bookmaker = 'betsafe', $sport = null, $league = null)
{
    $headers = [
        'authority: www.betsafe.lv',
        'accept: application/json, text/plain, */*',
        'accept-language: ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
        'brandid: e1d8f0f5-4918-44c5-b46e-d691eb8998c4',
        'cloudfront-viewer-country: ES',
        'content-type: application/json',
        'cookie: Acquisition_Status_Current=Prospect; Start_Acquisition=Prospect; Client_Status_Current=Prospect; Start_Client_Status=Prospect; Customer_Level=PC; _gcl_au=1.1.545631616.1692770928; Orientation=0; OBG-MARKET=en; OBG-LOBBY=sportsbook; cwr_u=31a4d190-2c36-4830-b6e2-0f914fa5db4b; sloc=%7B%22flags%22%3A%7B%22strm%22%3A1%2C%22customerFavourites%22%3A0%2C%22bbc%22%3A1%7D%2C%22segmentGuid%22%3A%229122d35d-d342-4343-a76d-283b4b18d152%22%7D; _ga=GA1.1.2040730919.1692770928; OptanonAlertBoxClosed=2023-09-04T19:46:12.271Z; CONSENT=%7B%22marketing%22%3A1%2C%22functional%22%3A1%2C%22performance%22%3A1%2C%22targeting%22%3A1%7D; _hjSessionUser_1736811=eyJpZCI6IjU0ODE1OTVjLTU3NTUtNTc3MS05ZTg4LWVkNDUzNzdlNTdjMyIsImNyZWF0ZWQiOjE2OTM4NTY3NzQ2NTksImV4aXN0aW5nIjp0cnVlfQ==; _gid=GA1.2.1810037385.1695735012; Initdone=1; TrafficType=Other Traffic; AffCookie=Missing AffCode; LoadAll=0; crw-_ga=2023-09-26-365; _hjIncludedInSessionSample_1736811=1; _hjSession_1736811=eyJpZCI6IjdmMWQyNWMyLTVkYjAtNGFkNy1hYTE3LTk1YzQ0YmZhODk2OSIsImNyZWF0ZWQiOjE2OTU3MzUwMTgyMzMsImluU2FtcGxlIjp0cnVlfQ==; _hjAbsoluteSessionInProgress=1; OptanonConsent=isIABGlobal=false&datestamp=Tue+Sep+26+2023+16%3A31%3A43+GMT%2B0300+(%D0%9C%D0%BE%D1%81%D0%BA%D0%B2%D0%B0%2C+%D1%81%D1%82%D0%B0%D0%BD%D0%B4%D0%B0%D1%80%D1%82%D0%BD%D0%BE%D0%B5+%D0%B2%D1%80%D0%B5%D0%BC%D1%8F)&version=6.39.0&hosts=&landingPath=NotLandingPage&groups=C0001%3A1%2CC0003%3A1%2CC0002%3A1%2CC0004%3A1&AwaitingReconsent=false&geolocation=ES%3BMD; cwr_s=eyJzZXNzaW9uSWQiOiJiNTg2YmJkOS1mN2U0LTRiZjgtYTU5ZC0xNTQ2Y2VhYzQ4ZDkiLCJyZWNvcmQiOmZhbHNlLCJldmVudENvdW50Ijo0MCwicGFnZSI6eyJwYWdlSWQiOiIvZW4vc3BvcnRzYm9vay9mb290YmFsbC9jaGFtcGlvbnMtbGVhZ3VlL2NoYW1waW9ucy1sZWFndWUiLCJpbnRlcmFjdGlvbiI6MCwicmVmZXJyZXIiOiIiLCJyZWZlcnJlckRvbWFpbiI6IiIsInN0YXJ0IjoxNjk1NzM1MDEyNzA3fX0=; _ga_S02BXVX55J=GS1.1.1695735017.3.1.1695735372.0.0.0',
        'correlationid: 43730fd4-2df9-4239-b9ff-da7d5f53afaa',
        'marketcode: en',
        'referer: https://www.betsafe.lv/en/sportsbook/football/champions-league/champions-league',
        'sec-ch-ua: "Google Chrome";v="117", "Not;A=Brand";v="8", "Chromium";v="117"',
        'sec-ch-ua-mobile: ?1',
        'sec-ch-ua-platform: "Android"',
        'sec-fetch-dest: empty',
        'sec-fetch-mode: cors',
        'sec-fetch-site: same-origin',
        'user-agent: Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Mobile Safari/537.36',
        'x-obg-channel: Web',
        'x-obg-country-code: ES',
        'x-obg-device: Mobile',
        'x-obg-experiments: ssrClientConfiguration',
        'x-sb-identifier: EVENT_TABLE_REQUEST',
        'x-sb-segment-guid: 9122d35d-d342-4343-a76d-283b4b18d152',
    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);

    if ($response === false) {
        echo 'Error: ' . curl_error($ch);
    } else {
        $data = json_decode($response);
        $data = json_decode(json_encode($data), true);
    }

    curl_close($ch);

    try {
        $sport_title = $sport;
        $existingSports = Sport::all();
        $sport = findOrCreateItemSport($existingSports, $sport ?? $sport_title, Sport::class, 52);

        $existingLeagues = $sport->leagues;
        $league = findOrCreateItemLeague($existingLeagues, $league ?? $league_title, League::class, 52, $sport->id);
        $marketIds = [];

        foreach ($data['data']['selections'] as $selection) {
            $marketId = $selection['marketId'];
            if (!in_array($marketId, $marketIds)) {
                $marketIds[] = $marketId;
            }
        }

        $output = [];

        foreach ($marketIds as $marketId) {
            $selections = array_filter($data['data']['selections'], function ($selection) use ($marketId) {
                return $selection['marketId'] === $marketId;
            });

            $homeOdds = '';
            $drawOdds = '';
            $awayOdds = '';

            $homeLabel = '';
            $awayLabel = '';

            foreach ($selections as $selection) {
                if ($selection['selectionTemplateId'] === 'HOME') {
                    $homeOdds = $selection['odds'];
                    if ($selection['participantLabel'] !== 'Draw') {
                        $homeLabel = $selection['participantLabel'];
                    }
                } elseif ($selection['selectionTemplateId'] === 'DRAW') {
                    $drawOdds = $selection['odds'];
                } elseif ($selection['selectionTemplateId'] === 'AWAY') {
                    $awayOdds = $selection['odds'];
                    if ($selection['participantLabel'] !== 'Draw') {
                        $awayLabel = $selection['participantLabel'];
                    }
                }
            }

            if (!empty($homeLabel) || !empty($homeOdds) || !empty($drawOdds) || !empty($awayOdds) || !empty($awayLabel)) {
                $output[] = [
                    'Home Label' => $homeLabel,
                    'Away Label' => $awayLabel,
                    'Home Odds' => $homeOdds,
                    'Draw Odds' => $drawOdds,
                    'Away Odds' => $awayOdds,
                ];
            }
        }

        foreach ($output as $values) {
            if ($sport_title === 'hockey' && empty($values['Draw Odds'])) {
                $team1 = $values['Home Label'];
                $team2 = $values['Away Label'];
                $odd_team1 = $values['Home Odds'];
                $draw = 0;
                $odd_team2 = $values['Away Odds'];

                foreach ($data['data']['events'] as $event) {
                    if ($event['participants'][0]['label'] == $team1 && $event['participants'][1]['label'] == $team2) {
                        $slug = $event['slug'];
                        $id = $event['id'];
                        $url_match = "https://www.betsafe.lv/en/sportsbook/$slug?eventId=$id";
                        break;
                    }
                }

                $existingGames = $league->games;
                $game = findOrCreateItemGame($existingGames, $team1, $team2, $date ?? now(), Game::class, 52, $league->id);

                if (!$game['reverse']) {
                    Odd::updateOrCreate(['game_id' => $game['item']->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team1, 'draw' => $draw, 'odd_team2' => $odd_team2, 'url' => $url_match ?? null]);
                } else {
                    Odd::updateOrCreate(['game_id' => $game['item']->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team2, 'draw' => $draw, 'odd_team2' => $odd_team1, 'url' => $url_match ?? null]);
                }
            } elseif ($sport_title !== 'hockey') {
                $team1 = $values['Home Label'];
                $team2 = $values['Away Label'];
                $odd_team1 = $values['Home Odds'];
                $draw = $values['Draw Odds'];
                $odd_team2 = $values['Away Odds'];

                foreach ($data['data']['events'] as $event) {
                    if ($event['participants'][0]['label'] == $team1 && $event['participants'][1]['label'] == $team2) {
                        $slug = $event['slug'];
                        $id = $event['id'];
                        $url_match = "https://www.betsafe.lv/en/sportsbook/$slug?eventId=$id";
                        break;
                    }
                }

                $existingGames = $league->games;
                $game = findOrCreateItemGame($existingGames, $team1, $team2, $date ?? now(), Game::class, 52, $league->id);

                if (!$game['reverse']) {
                    Odd::updateOrCreate(['game_id' => $game['item']->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team1, 'draw' => $draw, 'odd_team2' => $odd_team2, 'url' => $url_match ?? null]);
                } else {
                    Odd::updateOrCreate(['game_id' => $game['item']->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team2, 'draw' => $draw, 'odd_team2' => $odd_team1, 'url' => $url_match ?? null]);
                }
            }
        }

        echo 'betsafe';
    } catch (Exception $e) {
        // Обработка ошибки
        echo "Произошла ошибка: betsafe - $url" . $e->getMessage();
        // Или можете просто проигнорировать ошибку и продолжить выполнение кода дальше
    }
}
