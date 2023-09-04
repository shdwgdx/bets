<?php
echo  __DIR__;
require . '/../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../bootstrap/app.php';

// Загрузите инстанс приложения Laravel
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$app->instance('request', Illuminate\Http\Request::capture());
$kernel->bootstrap();
use App\Models\Game;
use App\Models\League;
use App\Models\Odd;
?>

<?php
require 'vendor/simpleHtmlDom/simple_html_dom.php';
require 'vendor/autoload.php'; // Подключение php-webdriver

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverWait;
use Facebook\WebDriver\WebDriverExpectedCondition;

function getPageSource($url)
{
    // Устанавливаем параметры для подключения к WebDriver
    $host = 'http://localhost:9515/wd/hub'; // Адрес и порт WebDriver сервера

    // Настройки для безголового режима Chrome
    $options = new ChromeOptions();
    $options->addArguments(['--headless', '--disable-gpu']);
    $capabilities = DesiredCapabilities::chrome();
    $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);

    // Создаем экземпляр RemoteWebDriver
    $driver = RemoteWebDriver::create($host, $capabilities);
    try {
        $driver->get($url);
        // Ждем загрузки всех элементов на странице
        $wait = new WebDriverWait($driver, 10);
        $wait->until(WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::className('game-markets-block')));

        $data = $driver->getPageSource();

        if ($data) {
            $html = new simple_html_dom();
            $html->load($data);

            // Найдем все контейнеры
            $eventContainers = $html->find('.game-markets-block .game-markets-row');
            foreach ($eventContainers as $eventContainer) {
                $titleEvent = $eventContainer->find('.game-markets__title', 0)->plaintext;
                $oddsContainers = $eventContainer->find('.odds');
                echo "$titleEvent \t\t\t\t\n";
                // echo count($oddsContainers);
                foreach ($oddsContainers as $oddsContainer) {
                    $selectionLabel = $oddsContainer->find('.odds__name', 0)->plaintext;
                    $coefficient = $oddsContainer->find('.odds__value', 0)->plaintext;

                    echo "$selectionLabel \t\t";
                    echo "$coefficient \n";
                }
            }
        }
    } finally {
        $driver->quit();
        $html->clear();
    }
}

function getMatchesSource($url)
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
        $wait->until(WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::className('sb-prematch')));
        $data = $driver->getPageSource();
        // echo $data;
        if ($data) {
            $html = new simple_html_dom();
            $html->load($data);

            $leagueName = strtolower('UEFA CHAMPIONS LEAGUE QUALIFICATION');
            $sport_id = 1;

            $league = null;
            $maxSimilarity = 0;
            // Извлекаем все лиги из базы данных
            $existingLeagues = League::all();

            foreach ($existingLeagues as $existingLeague) {
                $similarity = 0;
                similar_text($leagueName, $existingLeague->title, $similarity);

                if ($similarity > $maxSimilarity) {
                    $maxSimilarity = $similarity;
                    $league = $existingLeague;
                }
            }

            // Если схожесть > 52%, используем существующую лигу, иначе создаем новую
            if ($maxSimilarity > 52) {
                $leagueId = $league->id;
            } else {
                $newLeague = League::create([
                    'name' => $leagueName,
                    'sport_id' => $sport_id, // Укажи идентификатор спорта, к которому принадлежит лига
                ]);
                $leagueId = $newLeague->id;
            }

            // Найдем все контейнеры
            $container = $html->find('.sb-prematch', 0);
            $oddsTypeContainer = $container->find('.markets-select__entry');
            foreach ($oddsTypeContainer as $oddTypeContainer) {
                $oddsType = $oddTypeContainer->find('span', 0)->plaintext;
                echo $oddsType;
                break;
            }
            $matchContainers = $container->find('.game-entries');
            foreach ($matchContainers as $matchContainer) {
                $marketsIndicators = $matchContainer->find('.markets-indicators span');
                foreach ($marketsIndicators as $marketsIndicator) {
                    $market = $marketsIndicator->plaintext;
                    // echo $market;
                }
                $matches = $matchContainer->find('.single-game');
                foreach ($matches as $match) {
                    $teams = $match->find('.single-game-participants__entry');
                    $team1 = $teams[0];
                    $team2 = $teams[1];
                    echo $team1->plaintext;
                    echo $team2->plaintext;

                    // Создаем запись для игры
                    $game = Game::create([
                        'date' => '22.02.2024', // укажи дату игры здесь
                        'team1' => $team1,
                        'team2' => $team2,
                        'league_id' => $leagueId,
                    ]);

                    $odds = $match->find('.is-primary .odds');
                    $odd_team1 = $odds[0]->plaintext;
                    $draw = $odds[1]->plaintext;
                    $odd_team2 = $odds[2]->plaintext;

                    // Создаем запись для коэффициентов
                    Odd::create([
                        'odd_team1' => $odd_team1,
                        'draw' => $draw,
                        'odd_team2' => $odd_team2,
                        'game_id' => $game->id,
                        'bookmaker_name' => 'lvbet', // укажи название букмекера здесь
                    ]);
                }
            }
        }
    } finally {
        $driver->quit();
        $html->clear();
    }
}

$newLeague = League::create([
    'title' => 'поросята',
    'sport_id' => 1, // Укажи идентификатор спорта, к которому принадлежит лига
]);

echo $newLeague;
