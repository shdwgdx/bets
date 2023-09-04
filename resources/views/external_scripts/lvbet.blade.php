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
function getMatchesSourceLvbet($url, $bookmaker = 'lvbet', $sport = null, $league = null)
{
    // Устанавливаем параметры для подключения к WebDriver
    $host = 'http://localhost:9515'; // Адрес и порт WebDriver сервера

    // Настройки для безголового режима Chrome
    $options = new ChromeOptions();
    $options->addArguments(['--headless', '--disable-gpu', '--no-sandbox']);
    $capabilities = DesiredCapabilities::chrome();
    $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);

    // Создаем экземпляр RemoteWebDriver
    $driver = RemoteWebDriver::create($host, $capabilities);
    try {
        $driver->get($url);
        $wait = new WebDriverWait($driver, 10);
        $wait->until(WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::className('odds-group-entry')));
        $wait2 = new WebDriverWait($driver, 20);
        $wait2->until(WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector('ul.breadcrumbs li a span')));

        $data = $driver->getPageSource();
        // echo $data;
        if ($data) {
            $html = new simple_html_dom();
            $html->load($data);

            // $breadcrumbs = $html->find('ul.breadcrumbs li a span');
            // $futbolText = $breadcrumbs[1]->plaintext;
            // $championsLeagueText = $breadcrumbs[3]->plaintext;
            // echo $futbolText;
            // echo $championsLeagueText;
            // $sport_title = strtolower($futbolText);
            // $league_title = strtolower($championsLeagueText);
            if ($html) {
                $existingSports = Sport::all();
                $sport = findOrCreateItemSport($existingSports, $sport ?? $sport_title, Sport::class, 52);

                $existingLeagues = $sport->leagues;
                $league = findOrCreateItemLeague($existingLeagues, $league ?? $league_title, League::class, 52, $sport->id);

                $container = $html->find('.sb-prematch', 0);
                $matches = $container->find('.game-entries .single-game');
                foreach ($matches as $match) {
                    //Название команд
                    $teams = $match->find('.single-game-participants__entry');
                    $team1 = $teams[0]->plaintext;
                    $team2 = $teams[1]->plaintext;

                    //Дата

                    if ($match->find('.single-game-date', 0)) {
                        $dateElement = $match->find('.single-game-date', 0);
                        $dateElement->find('.single-game-date__entry.has-day', 0)->plaintext;
                        $dateStr = trim($dateElement->find('.single-game-date__entry.has-day', 0)->plaintext);
                        $timeStr = trim($dateElement->find('.single-game-date__entry.has-hour', 0)->plaintext);
                        $combinedString = $dateStr . ' ' . $timeStr;
                        $date = Carbon::createFromFormat('d.m H:i', $combinedString)->format('Y-m-d H:i:s');
                    }

                    // Создаем запись для игры
                    $existingGames = $league->games;
                    $game = findOrCreateItemGame($existingGames, $team1, $team2, $date = now(), Game::class, 52, $league->id);
                    $oddscontainer = $match->find('.odds-group-entry', 0);
                    $odds = $oddscontainer->find('.odds');
                    if (!$oddscontainer->find('.is-empty')) {
                        $odd_team1 = $odds[0]->plaintext;
                        $draw = is_numeric($odds[1]->plaintext) ? $odds[1]->plaintext : 0;
                        $odd_team2 = $odds[2]->plaintext;
                    }
                    if (!$game['reverse']) {
                        Odd::updateOrCreate(['game_id' => $game['item']->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team1, 'draw' => $draw, 'odd_team2' => $odd_team2]);
                    } else {
                        Odd::updateOrCreate(['game_id' => $game['item']->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team2, 'draw' => $draw, 'odd_team2' => $odd_team1]);
                    }
                }
            }
        }
        echo 'lvbet';
        $html->clear();
    } catch (Exception $e) {
        // Обработка ошибки
        echo "Произошла ошибка: lvbet - $url" . $e->getMessage();
        // Или можете просто проигнорировать ошибку и продолжить выполнение кода дальше
    } finally {
        $driver->quit();
    }
}
