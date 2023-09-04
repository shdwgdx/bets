<?php
require 'vendor/simpleHtmlDom/simple_html_dom.php';
require 'vendor/autoload.php'; // Подключение php-webdriver

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverWait;
use Facebook\WebDriver\WebDriverExpectedCondition;

require_once __DIR__ . '/../../../vendor/autoload.php';

$app = require_once __DIR__ . '/../../../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
use Carbon\Carbon;
use App\Models\Game;
use App\Models\League;
use App\Models\Odd;
use App\Models\Sport;

function findOrCreateItemSport($existingItems, $name, $className, $similarityThreshold)
{
    $item = null;
    $maxSimilarity = 0;

    foreach ($existingItems as $existingItem) {
        $similarity = 0;
        similar_text($name, $existingItem->title, $similarity);

        if ($similarity > $maxSimilarity) {
            $maxSimilarity = $similarity;
            $item = $existingItem;
        }
    }

    if ($maxSimilarity > $similarityThreshold) {
        return $item;
    } else {
        $newItem = $className::create([
            'title' => $name,
        ]);
        return $newItem;
    }
}
function findOrCreateItemLeague($existingItems, $name, $className, $similarityThreshold, $sportId)
{
    $item = null;
    $maxSimilarity = 0;

    foreach ($existingItems as $existingItem) {
        $similarity = 0;
        similar_text($name, $existingItem->title, $similarity);

        if ($similarity > $maxSimilarity) {
            $maxSimilarity = $similarity;
            $item = $existingItem;
        }
    }

    if ($maxSimilarity > $similarityThreshold) {
        return $item;
    } else {
        $newItem = $className::create([
            'title' => $name,
            'sport_id' => $sportId,
        ]);
        return $newItem;
    }
}

function findOrCreateItemGame($existingItems, $team1, $team2, $date, $className, $similarityThreshold, $leagueId)
{
    $item = null;
    $maxSimilarity = 0;

    foreach ($existingItems as $existingItem) {
        $similarity = 0;
        similar_text($team1, $existingItem->team1, $similarity);

        if ($similarity > $maxSimilarity) {
            $maxSimilarity = $similarity;
            $item = $existingItem;
        }
    }

    if ($maxSimilarity > $similarityThreshold) {
        return $item;
    } else {
        $newItem = $className::create([
            'date' => $date,
            'team1' => $team1,
            'team2' => $team2,
            'league_id' => $leagueId,
        ]);
        return $newItem;
    }
}

function getMatchesSourceBetsafe($url, $bookmaker = 'betsafe')
{
    // Устанавливаем параметры для подключения к WebDriver
    $host = 'http://localhost:9515'; // Адрес и порт WebDriver сервера

    // Настройки для безголового режима Chrome
    $options = new ChromeOptions();
    $options->addArguments(['--headless', '--disable-gpu', '--window-size=1920,1080', '--ignore-certificate-errors', '--allow-running-insecure-content', '--disable-extensions', "--proxy-server='direct://'", '--proxy-bypass-list=*', '--start-maximized', '--disable-dev-shm-usage']);
    $capabilities = DesiredCapabilities::chrome();
    $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);
    // $options->addArguments(['--user-agent=Y	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.36 Edg/116.0.1938.54']);

    // Создаем экземпляр RemoteWebDriver
    $driver = RemoteWebDriver::create($host, $capabilities);
    try {
        $driver->get($url);
        // Дождитесь, пока страница полностью загрузится

        $data = $driver->getPageSource();
        // echo $data;
        if ($data) {
            $html = new simple_html_dom();
            $html->load($data);
            // Найдем все контейнеры
            $containers = $html->find('.obg-event-row-event-container');
            //Спорт и лига
            $ligaElement = $containers[0]->find('.obg-event-info-category-label', 0);
            $sport_liga = explode('/', $ligaElement->plaintext);

            $sport_title = strtolower($sport_liga[0]);
            $league_title = strtolower($sport_liga[1]);

            $existingSports = Sport::all();
            $sport = findOrCreateItemSport($existingSports, $sport_title, Sport::class, 52);

            $existingLeagues = $sport->leagues;
            $league = findOrCreateItemLeague($existingLeagues, $league_title, League::class, 52, $sport->id);

            // Проходимся по найденным контейнерам и извлекаем информацию
            foreach ($containers as $container) {
                $eventElement = $container->find('.obg-event-row-market-label', 0);
                $event = $eventElement->plaintext;
                // Название команды
                $participantNameElements = $container->find('.obg-event-info-participant-name');
                $team1 = $participantNameElements[0]->plaintext;
                $team2 = $participantNameElements[1]->plaintext;
                // Дата
                $dateElement = $container->find('.obg-event-status', 0);
                $dateString = trim($dateElement->find('span', 0)->plaintext);
                $date = Carbon::createFromFormat('d M, H:i', $dateString)->format('Y-m-d H:i:s');

                // Здесь выполнение запроса к базе данных, используя значение $date для столбца datetime

                // Создаем запись для игры
                $existingGames = $league->games;
                $game = findOrCreateItemGame($existingGames, $team1, $team2, $date, Game::class, 52, $league->id);

                // коэффициенты событий
                $coefficientElements = $container->find('.obg-numeric-change span');
                $odd_team1 = $coefficientElements[0]->plaintext;
                $draw = $coefficientElements[1]->plaintext;
                $odd_team2 = $coefficientElements[2]->plaintext;

                Odd::updateOrCreate(['game_id' => $game->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team1, 'draw' => $draw, 'odd_team2' => $odd_team2]);
            }
        }
    } finally {
        $driver->quit();
        $html->clear();
    }
}

function getMatchesSourceLvbet($url, $bookmaker = 'lvbet')
{
    // Устанавливаем параметры для подключения к WebDriver
    $host = 'http://localhost:9515'; // Адрес и порт WebDriver сервера

    // Настройки для безголового режима Chrome
    $options = new ChromeOptions();
    $options->addArguments(['--headless', '--disable-gpu']);
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
            $futbolText = $breadcrumbs[1]->plaintext;
            $championsLeagueText = $breadcrumbs[3]->plaintext;

            $sport_title = strtolower($futbolText);
            $league_title = strtolower($championsLeagueText);
            echo $sport_title;
            echo $league_title;
            $existingSports = Sport::all();
            $sport = findOrCreateItemSport($existingSports, $sport_title, Sport::class, 52);

            $existingLeagues = $sport->leagues;
            $league = findOrCreateItemLeague($existingLeagues, $league_title, League::class, 52, $sport->id);

            $container = $html->find('.sb-prematch', 0);
            $matches = $container->find('.game-entries .single-game');
            foreach ($matches as $match) {
                //Название команд
                $teams = $match->find('.single-game-participants__entry');
                $team1 = $teams[0]->plaintext;
                $team2 = $teams[1]->plaintext;

                //Дата
                $dateElement = $match->find('.single-game-date', 0);
                $dateStr = trim($dateElement->find('.single-game-date__entry.has-day', 0)->plaintext);
                $timeStr = trim($dateElement->find('.single-game-date__entry.has-hour', 0)->plaintext);
                $combinedString = $dateStr . ' ' . $timeStr;
                $date = Carbon::createFromFormat('d.m H:i', $combinedString)->format('Y-m-d H:i:s');

                // Создаем запись для игры
                $existingGames = $league->games;
                $game = findOrCreateItemGame($existingGames, $team1, $team2, $date, Game::class, 52, $league->id);

                $odds = $match->find('.is-primary .odds');
                $odd_team1 = $odds[0]->plaintext;
                $draw = is_numeric($odds[1]->plaintext) ? $odds[1]->plaintext : 0;
                $odd_team2 = $odds[2]->plaintext;

                Odd::updateOrCreate(['game_id' => $game->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team1, 'draw' => $draw, 'odd_team2' => $odd_team2]);
            }
        }
    } finally {
        $driver->quit();
        $html->clear();
    }
}

function getMatchesSourceMrgreen($url, $bookmaker = 'mrgreen')
{
    // Устанавливаем параметры для подключения к WebDriver
    $host = 'http://localhost:9515'; // Адрес и порт WebDriver сервера

    // Настройки для безголового режима Chrome
    $options = new ChromeOptions();
    $options->addArguments(['--headless', '--disable-gpu']);
    $options->addArguments(['--user-agent=Y	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.36 Edg/116.0.1938.54']);
    $capabilities = DesiredCapabilities::chrome();
    $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);

    // Создаем экземпляр RemoteWebDriver
    $driver = RemoteWebDriver::create($host, $capabilities);

    try {
        $driver->get($url);

        $wait = new WebDriverWait($driver, 15);
        $element = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::tagName('magic-sportsbook')));

        $shadowRoot = $element->getShadowRoot();
        $iframe = $shadowRoot->findElement(WebDriverBy::className('sportnco-sportsbook'));

        $driver->switchTo()->frame($iframe);
        $data = $driver->getPageSource();

        if ($data) {
            $html = new simple_html_dom();
            $html->load($data);

            // Нахождение нужных элементов с помощью CSS-селекторов
            $breadcrumbLists = $html->find('#breadcrumb-list-drag-scroll-alone ul li');
            $footballText = trim($breadcrumbLists[1]->plaintext);
            $championsLeagueText = trim($breadcrumbLists[3]->plaintext);

            $sport_title = strtolower($footballText);
            $league_title = strtolower($championsLeagueText);
            echo $sport_title;
            echo $league_title;
            $existingSports = Sport::all();
            $sport = findOrCreateItemSport($existingSports, $sport_title, Sport::class, 52);

            $existingLeagues = $sport->leagues;
            $league = findOrCreateItemLeague($existingLeagues, $league_title, League::class, 52, $sport->id);

            // Найдем все контейнеры
            $lines = $html->find('.lines');
            foreach ($lines as $line) {
                //Команды
                $teams = $line->find('[class^=actor-]');
                $team1 = $teams[0]->plaintext;
                $team2 = $teams[1]->plaintext;

                // Нахождение элемента, содержащего дату и время
                $dateElement = $line->find('.date-event', 0);
                // Извлечение значения даты и времени
                $dateValue = trim($dateElement->find('span', 0)->plaintext);
                $timeValue = trim($dateElement->find('span', 1)->plaintext);
                // Преобразование значения даты и времени с помощью Carbon
                $date = Carbon::createFromFormat('D d M H:i', $dateValue . ' ' . $timeValue)->format('Y-m-d H:i:s');
                $event = strtolower(trim($line->find('.question-list span', 0)->plaintext));
                if ($event == 'match result' || $event == 'money line') {
                    $existingGames = $league->games;
                    $game = findOrCreateItemGame($existingGames, $team1, $team2, $date, Game::class, 52, $league->id);
                    $oddsBox = $line->find('.odds-box-total', 0);
                    if (!empty($oddsBox)) {
                        $elements = $oddsBox->find('span.odd.vertical.centered');

                        if (count($elements) == 3 && $event == 'match result') {
                            $odd_team1 = $elements[0]->find('.container-odd-and-trend', 0)->plaintext;
                            $draw = $elements[1]->find('.container-odd-and-trend', 0)->plaintext;
                            $odd_team2 = $elements[2]->find('.container-odd-and-trend', 0)->plaintext;
                        } elseif (count($elements) == 2 && $event == 'money line') {
                            $odd_team1 = $elements[0]->find('.container-odd-and-trend', 0)->plaintext;
                            $odd_team2 = $elements[1]->find('.container-odd-and-trend', 0)->plaintext;
                            $draw = 0;
                        }

                        Odd::updateOrCreate(['game_id' => $game->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team1, 'draw' => $draw, 'odd_team2' => $odd_team2]);
                    }
                }
            }
        }
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
    } finally {
        $driver->quit();
        $html->clear();
    }
}

function getMatchesSourceOlybet($url, $bookmaker = 'olybet')
{
    // Устанавливаем параметры для подключения к WebDriver
    $host = 'http://localhost:9515'; // Адрес и порт WebDriver сервера

    // Настройки для безголового режима Chrome
    $options = new ChromeOptions();
    $options->addArguments(['--headless', '--disable-gpu', '--window-size=1440,1400', '--ignore-certificate-errors', '--allow-running-insecure-content', '--disable-extensions', "--proxy-server='direct://'", '--proxy-bypass-list=*', '--start-maximized', '--disable-dev-shm-usage']);
    $options->addArguments(['--user-agent=Y	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.36 Edg/116.0.1938.54']);
    $capabilities = DesiredCapabilities::chrome();
    $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);

    // Создаем экземпляр RemoteWebDriver
    $driver = RemoteWebDriver::create($host, $capabilities);
    try {
        $driver->get($url);
        $wait = new WebDriverWait($driver, 15);
        $wait->until(WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::id('bcsportsbookiframe')));

        $iframe = $driver->findElement(WebDriverBy::id('bcsportsbookiframe'));
        // Переключиться в iframe

        $driver->switchTo()->frame($iframe);

        $data = $driver->getPageSource();

        if ($data) {
            $html = new simple_html_dom();
            $html->load($data);
            // echo $data;
            // Найдем все контейнеры
            $container = $html->find('.prematch-games-list', 0);
            $league_title = $container->find('.prematch-games-list-title-v3 h2 span', 3)->innertext;
            echo $league_title;
            $sport_title = strtolower('football');
            $league_title = strtolower($league_title);

            $existingSports = Sport::all();
            $sport = findOrCreateItemSport($existingSports, $sport_title, Sport::class, 52);

            $existingLeagues = $sport->leagues;
            $league = findOrCreateItemLeague($existingLeagues, $league_title, League::class, 52, $sport->id);

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

                    //Время
                    $timeString = trim($row->find('.time-game-v3 p', 0)->plaintext);
                    // Преобразование значения даты и времени с помощью Carbon
                    $date = Carbon::createFromFormat('d.m.y H:i', $dateString . ' ' . $timeString)->format('Y-m-d H:i:s');

                    $existingGames = $league->games;
                    $game = findOrCreateItemGame($existingGames, $team1, $team2, $date, Game::class, 52, $league->id);

                    $odds = $row->find('.aic-odd-container');
                    $odd_team1 = $odds[0]->plaintext;
                    $draw = $odds[1]->plaintext;
                    $odd_team2 = $odds[2]->plaintext;
                    Odd::updateOrCreate(['game_id' => $game->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team1, 'draw' => $draw, 'odd_team2' => $odd_team2]);
                }
            }
        }
    } finally {
        $driver->quit();
        $html->clear();
    }
}
// getMatchesSourceBetsafe('https://www.betsafe.lv/en/sportsbook/ice-hockey/nhl/nhl');
getMatchesSourceLvbet('https://lvbet.lv/sports/en/pre-matches/multiple--?leagues=61118');
getMatchesSourceMrgreen('https://www.mrgreen.lv/en/sports/competition/206-nba');
// getMatchesSourceOlybet('https://www.olybet.lv/sports?competition=18286520&game=22912934&region=20001&type=0&sport=1&lang=en');
