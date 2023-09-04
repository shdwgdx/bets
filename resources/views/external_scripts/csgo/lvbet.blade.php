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
    // Устанавливаем параметры для подключения к WebDriver
    $host = 'http://localhost:9515'; // Адрес и порт WebDriver сервера

    // Настройки для безголового режима Chrome
    $options = new ChromeOptions();
    $options->addArguments(['--headless', '--no-sandbox', '--disable-gpu']);
    $capabilities = DesiredCapabilities::chrome();
    $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);

    // Создаем экземпляр RemoteWebDriver
    $driver = RemoteWebDriver::create($host, $capabilities);
    try {
        $driver->get($url);
        $wait = new WebDriverWait($driver, 10);
        $wait->until(WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::className('odds-group-entry')));
        $wait = new WebDriverWait($driver, 10);
        $wait->until(WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector('ul.breadcrumbs')));

        $data = $driver->getPageSource();
        // echo $data;
        if ($data) {
            $html = new simple_html_dom();
            $html->load($data);

            $breadcrumbs = $html->find('ul.breadcrumbs li a span');
            $sportText = $breadcrumbs[1]->plaintext;
            $sport_title = strtolower(trim($sportText));

            $existingSports = Sport::all();
            $sport = findOrCreateItemSport($existingSports, 'csgo', Sport::class, 52);

            $container = $html->find('.sb-prematch', 0);
            $tables = $container->find('.odds-table__block');
            foreach ($tables as $table) {
                $matches = $table->find('.game-entries .single-game');

                $league_title = strtolower(trim($table->find('.basic-headline__title', 0)->plaintext));

                $existingLeagues = $sport->leagues;
                $league = findOrCreateItemLeagueCsgo($existingLeagues, $league_title, League::class, 52, $sport->id);

                foreach ($matches as $match) {
                    $odds = $match->find('.is-primary .odds');
                    if (!$match->find('.is-primary .is-empty')) {
                        //Название команд
                        $teams = $match->find('.single-game-participants__entry');
                        $team1 = $teams[0]->plaintext;
                        $team2 = $teams[1]->plaintext;

                        //Дата
                        if ($match->find('.single-game-date', 0)) {
                            // $dateElement = $match->find('.single-game-date', 0);
                            // echo $dateElement->plaintext;
                            // $dateStr = trim($dateElement->find('.single-game-date__entry.has-day', 0)->plaintext);
                            // $timeStr = trim($dateElement->find('.single-game-date__entry.has-hour', 0)->plaintext);
                            // $combinedString = $dateStr . ' ' . $timeStr;
                            // $date = Carbon::createFromFormat('d.m H:i', $combinedString)->format('Y-m-d H:i:s');

                            // Создаем запись для игры
                            $existingGames = $league->games;
                            $game = findOrCreateItemGameCsgo($existingGames, $team1, $team2, $date ?? now(), Game::class, 52, $league->id);

                            $odd_team1 = $odds[0]->plaintext;
                            $draw = is_numeric($odds[1]->plaintext) ? $odds[1]->plaintext : 0;
                            $odd_team2 = $odds[2]->plaintext;

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
        echo 'lvbetCsgo';
        $html->clear();
    } catch (Exception $e) {
        // Обработка ошибки
        echo "Произошла ошибка: lvbetCsgo - $url" . $e->getMessage();
        // Или можете просто проигнорировать ошибку и продолжить выполнение кода дальше
    } finally {
        $driver->quit();
    }
}
