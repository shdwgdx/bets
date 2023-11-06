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
function getMatchesSourceOlybet($url, $bookmaker = 'olybet', $sport = null, $league = null)
{
    try {
        // Устанавливаем параметры для подключения к WebDriver
        $host = 'http://localhost:9515'; // Адрес и порт WebDriver сервера

        // Настройки для безголового режима Chrome
        $options = new ChromeOptions();
        $options->addArguments(['--headless', '--disable-gpu', '--no-sandbox', '--window-size=1440,1400', '--ignore-certificate-errors', '--allow-running-insecure-content', '--disable-extensions', "--proxy-server='direct://'", '--proxy-bypass-list=*', '--start-maximized', '--disable-dev-shm-usage']);
        $options->addArguments(['--user-agent=Y	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.36 Edg/116.0.1938.54']);
        $capabilities = DesiredCapabilities::chrome();
        $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);

        // Создаем экземпляр RemoteWebDriver
        $driver = RemoteWebDriver::create($host, $capabilities);
        $driver->get($url);
        $wait = new WebDriverWait($driver, 15);
        $wait->until(WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::id('bcsportsbookiframe')));

        $iframe = $driver->findElement(WebDriverBy::id('bcsportsbookiframe'));
        // Переключиться в iframe
        // $wait = new WebDriverWait($driver, 10);

        // // Ждем, пока элемент с классом .aic-hdp-row станет видимым
        // $element = $wait->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('.aic-hdp-row')));

        // // Выполняем клик по найденному элементу
        // $element->click();

        $driver->switchTo()->frame($iframe);
        $wait2 = new WebDriverWait($driver, 20);
        $wait2->until(WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector('.prematch-games-list-title-v3 h2 span')));
        $data = $driver->getPageSource();

        if ($data) {
            $html = new simple_html_dom();
            $html->load($data);
            // Находим элемент с классом 'prematch-header-row-v3'
            // $matchElement = $html->find('.prematch-header-row-v3', 0);

            // Получаем информацию о лиге
            // $leagueElement = $matchElement->find('.prematch-competition-title-v3 span', 2)->plaintext;
            // echo 'Лига: ' . $leagueElement;
            // echo $data;
            // Найдем все контейнеры
            $container = $html->find('.prematch-games-list', 0);

            // $league_title = $container->find('.prematch-games-list-title-v3 h2 span', 3)->innertext;
            // echo $league_title;
            // $sport_title = strtolower('football');
            // $league_title = strtolower($league_title);
            $url_match = $url;
            $existingSports = Sport::all();
            $sport = findOrCreateItemSport($existingSports, $sport ?? $sport_title, Sport::class, 52);

            $existingLeagues = $sport->leagues;
            $league = findOrCreateItemLeague($existingLeagues, $league ?? $league_title, League::class, 52, $sport->id);
            $content_x = strtolower($html->find('.multicolumn-table .aic-table-head-second', 0)->plaintext);

            $tables = $container->find('.multicolumn-table');
            for ($i = 1; $i < count($tables); $i++) {
                $table = $tables[$i];
                $dateString = trim($table->find('.time-title-view-v3', 0)->plaintext);
                $rows = $table->find('.aic-hdp-row');

                //Создание игр
                foreach ($rows as $row) {
                    //Команды
                    $teams = $row->find('.aic-team-names');
                    $team1 = trim($teams[0]->plaintext);
                    $team2 = trim($teams[1]->plaintext);

                    // //Время
                    // $timeString = trim($row->find('.time-game-v3 p', 0)->plaintext);
                    // // Преобразование значения даты и времени с помощью Carbon
                    // $date = Carbon::createFromFormat('d.m.y H:i', $dateString . ' ' . $timeString)->format('Y-m-d H:i:s');

                    $existingGames = $league->games;
                    $game = findOrCreateItemGame($existingGames, $team1, $team2, $date = now(), Game::class, 52, $league->id);

                    $odds = $row->find('.aic-odd-container');
                    if (strpos($content_x, 'x')) {
                        $odd_team1 = $odds[0]->plaintext;
                        $draw = $odds[1]->plaintext;
                        $odd_team2 = $odds[2]->plaintext;
                    } else {
                        $odd_team1 = $odds[0]->plaintext;
                        $odd_team2 = $odds[1]->plaintext;
                        $draw = 0;
                    }

                    if (!$game['reverse']) {
                        Odd::updateOrCreate(['game_id' => $game['item']->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team1, 'draw' => $draw, 'odd_team2' => $odd_team2, 'url' => $url_match ?? null]);
                    } else {
                        Odd::updateOrCreate(['game_id' => $game['item']->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team2, 'draw' => $draw, 'odd_team2' => $odd_team1, 'url' => $url_match ?? null]);
                    }
                }
            }
        }
        echo 'olybet';
        $html->clear();
    } catch (Exception $e) {
        // Обработка ошибки
        echo "Произошла ошибка: olybet - $url" . $e->getMessage();
        // Или можете просто проигнорировать ошибку и продолжить выполнение кода дальше
    } finally {
        $driver->quit();
    }
}
