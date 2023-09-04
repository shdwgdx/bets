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

function getMatchesSource($url = 'https://www.mrgreen.lv/en/sports/competition/6674-champions-league', $bookmaker = 'mrgreen')
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
            echo $html;
            // // Нахождение нужных элементов с помощью CSS-селекторов
            // $breadcrumbLists = $html->find('#breadcrumb-list-drag-scroll-alone ul li');
            // $footballText = trim($breadcrumbLists[1]->plaintext);
            // $championsLeagueText = trim($breadcrumbLists[3]->plaintext);

            // $sport_title = strtolower($footballText);
            // $league_title = strtolower($championsLeagueText);

            // $existingSports = Sport::all();
            // $sport = findOrCreateItemSport($existingSports, $sport_title, Sport::class, 52);

            // $existingLeagues = $sport->leagues;
            // $league = findOrCreateItemLeague($existingLeagues, $league_title, League::class, 52, $sport->id);

            // Найдем все контейнеры
            // $lines = $html->find('.lines');
            // foreach ($lines as $line) {
            //     $teams = $line->find('[class^=actor-]');
            //     $team1 = $teams[0]->plaintext;
            //     $team2 = $teams[1]->plaintext;

            //     // Нахождение элемента, содержащего дату и время
            //     $dateElement = $line->find('.date-event', 0);
            //     // Извлечение значения даты и времени
            //     $dateValue = trim($dateElement->find('span', 0)->plaintext);
            //     $timeValue = trim($dateElement->find('span', 1)->plaintext);
            //     // Преобразование значения даты и времени с помощью Carbon
            //     $date = Carbon::createFromFormat('D d M H:i', $dateValue . ' ' . $timeValue)->format('Y-m-d H:i:s');
            //     // Вывод значения datetime
            //     echo 'DateTime: ' . $date . '<br>';

            //     $existingGames = $league->games;
            //     $game = findOrCreateItemGame($existingGames, $team1, $team2, $date, Game::class, 52, $league->id);

            //     $oddsBox = $line->find('.odds-box-total', 0);
            //     if (!empty($oddsBox)) {
            //         // $event = $line->find('.question-list span', 0)->plaintext;
            //         // echo "\t\t\t\t Событие: $event \n";
            //         $elements = $oddsBox->find('span.odd.vertical.centered');

            //         $odd_team1 = $elements[0]->find('.container-odd-and-trend', 0)->plaintext;
            //         $draw = $elements[1]->find('.container-odd-and-trend', 0)->plaintext;
            //         $odd_team2 = $elements[2]->find('.container-odd-and-trend', 0)->plaintext;

            //         Odd::updateOrCreate(['game_id' => $game->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team1, 'draw' => $draw, 'odd_team2' => $odd_team2]);
            //     }
            // }
        }
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
    } finally {
        $driver->quit();
        // $html->clear();
    }
}
getMatchesSource();
