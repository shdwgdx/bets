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
    // Устанавливаем параметры для подключения к WebDriver
    $host = 'http://localhost:9515'; // Адрес и порт WebDriver сервера

    // Настройки для безголового режима Chrome
    $options = new ChromeOptions();
    $options->addArguments(['--headless', '--disable-gpu', '--no-sandbox', '--window-size=1920,1080', '--ignore-certificate-errors', '--allow-running-insecure-content', '--disable-extensions', "--proxy-server='direct://'", '--proxy-bypass-list=*', '--start-maximized', '--disable-dev-shm-usage']);
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
            // //Спорт и лига
            // $ligaElement = $containers[0]->find('.obg-event-info-category-label', 0);
            // $sport_liga = explode('/', $ligaElement->plaintext);

            $sport_title = strtolower($sport);
            // $league_title = strtolower($sport_liga[1]);
            $existingSports = Sport::all();
            $sport = findOrCreateItemSport($existingSports, $sport ?? $sport_title, Sport::class, 52);

            $existingLeagues = $sport->leagues;
            $league = findOrCreateItemLeague($existingLeagues, $league ?? $league_title, League::class, 52, $sport->id);

            // Проходимся по найденным контейнерам и извлекаем информацию
            foreach ($containers as $container) {
                if ($sport_title == 'hockey') {
                    $event = strtolower(trim($container->find('.obg-event-row-market-label', 1)->plaintext));
                } else {
                    $event = strtolower(trim($container->find('.obg-event-row-market-label', 0)->plaintext));
                }
                if ($event == 'match result' || $event == 'match winner' || 'moneyline') {
                    // Название команды
                    $participantNameElements = $container->find('.obg-event-info-participant-name');
                    $team1 = $participantNameElements[0]->plaintext;
                    $team2 = $participantNameElements[1]->plaintext;
                    // Дата
                    // $dateElement = $container->find('.obg-event-status', 0);
                    // $dateString = trim($dateElement->find('span', 0)->plaintext);
                    // $date = Carbon::createFromFormat('d M, H:i', $dateString)->format('Y-m-d H:i:s');

                    // Здесь выполнение запроса к базе данных, используя значение $date для столбца datetime

                    // Создаем запись для игры
                    $existingGames = $league->games;
                    $game = findOrCreateItemGame($existingGames, $team1, $team2, $date = now(), Game::class, 52, $league->id);

                    // коэффициенты событий
                    $coefficientElements = $container->find('.obg-numeric-change span');

                    if ($event == 'match result') {
                        $odd_team1 = $coefficientElements[0]->plaintext;
                        $draw = $coefficientElements[1]->plaintext;
                        $odd_team2 = $coefficientElements[2]->plaintext;
                    } elseif ($event == 'match winner') {
                        $odd_team1 = $coefficientElements[0]->plaintext;
                        $odd_team2 = $coefficientElements[1]->plaintext;
                        $draw = 0;
                    } elseif ($event == 'moneyline') {
                        if (isset($coefficientElements[3]) && isset($coefficientElements[4])) {
                            $odd_team1 = $coefficientElements[3]->plaintext;
                            $odd_team2 = $coefficientElements[4]->plaintext;
                            $draw = 0;
                        } else {
                            $odd_team1 = $coefficientElements[0]->plaintext;
                            $odd_team2 = $coefficientElements[1]->plaintext;
                            $draw = 0;
                        }
                    }

                    if (!$game['reverse']) {
                        Odd::updateOrCreate(['game_id' => $game['item']->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team1, 'draw' => $draw, 'odd_team2' => $odd_team2]);
                    } else {
                        Odd::updateOrCreate(['game_id' => $game['item']->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team2, 'draw' => $draw, 'odd_team2' => $odd_team1]);
                    }
                }
            }
        }
        echo 'betsafe';
        $html->clear();
    } catch (Exception $e) {
        // Обработка ошибки
        echo "Произошла ошибка: betsafe - $url" . $e->getMessage();
        // Или можете просто проигнорировать ошибку и продолжить выполнение кода дальше
    } finally {
        $driver->quit();
    }
}
