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

function getMatchesSourceTonybet($url = 'https://sb2frontend-altenar2.biahosted.com/api/Sportsbook/GetEvents?timezoneOffset=-180&langId=86&skinName=rabona&configId=12&culture=en-CA&countryCode=ES&deviceType=Desktop&numformat=en&integration=rabona&sportids=67&categoryids=0&champids=2980&group=AllEvents&period=periodall&withLive=false&outrightsDisplay=none&marketTypeIds=&couponType=0&marketGroupId=0&startDate=2023-09-05T15%3A58%3A00.000Z&endDate=2023-09-12T15%3A58%3A00.000Z', $bookmaker = 'tonybet', $sport = null, $league = null)
{
    // Используем функцию file_get_contents() для выполнения GET-запроса и получения JSON данных
    $jsonData = file_get_contents($url);

    // Парсим полученные данные в формате JSON
    $data = json_decode($jsonData);

    // Выводим полученные данные
    // var_dump($data);
    try {
        $matches = $data->Result->Items[0]->Events;
        foreach ($matches as $match) {
            $team1 = $match->Competitors[0]->Name;
            $team2 = $match->Competitors[1]->Name;
            if (count($match->Items[0]->Items) == 3) {
                $odd_team1 = substr($match->Items[0]->Items[0]->Price, 0, strpos($match->Items[0]->Items[0]->Price, '.') + 3);
                $draw = substr($match->Items[0]->Items[1]->Price, 0, strpos($match->Items[0]->Items[0]->Price, '.') + 3);
                $odd_team2 = substr($match->Items[0]->Items[2]->Price, 0, strpos($match->Items[0]->Items[0]->Price, '.') + 3);
            } else {
                $odd_team1 = substr($match->Items[0]->Items[0]->Price, 0, strpos($match->Items[0]->Items[0]->Price, '.') + 3);
                $odd_team2 = substr($match->Items[0]->Items[1]->Price, 0, strpos($match->Items[0]->Items[0]->Price, '.') + 3);
                $draw = 0;
            }
            echo "competitor1Name: $team1\n";
            echo "competitor2Name: $team2\n";
            echo " $odd_team1\n";
            echo "draw: $draw\n";
            echo " $odd_team2\n";
            echo "-------------------\n";
        }
        echo 'tonybet';
    } catch (Exception $e) {
        // Обработка ошибки
        echo "Произошла ошибка: tonybet - $url" . $e->getMessage();
        // Или можете просто проигнорировать ошибку и продолжить выполнение кода дальше
    }
}
getMatchesSourceTonybet();
