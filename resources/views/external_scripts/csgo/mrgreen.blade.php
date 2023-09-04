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
    // Устанавливаем параметры для подключения к WebDriver
    $host = 'http://localhost:9515'; // Адрес и порт WebDriver сервера

    // Настройки для безголового режима Chrome
    $options = new ChromeOptions();
    $options->addArguments(['--headless', '--no-sandbox', '--disable-gpu']);
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
        // Находим элемент с содержанием "Up Next" и кликаем по нему
        if ($driver->findElement(WebDriverBy::xpath("//span[contains(text(), 'Up Next')]"))) {
            $element = $driver->findElement(WebDriverBy::xpath("//span[contains(text(), 'Up Next')]"));
            $element->click();
        }

        $data = $driver->getPageSource();

        if ($data) {
            $html = new simple_html_dom();
            $html->load($data);

            // Нахождение нужных элементов с помощью CSS-селекторов
            $breadcrumbLists = $html->find('#breadcrumb-list-drag-scroll-alone ul li');
            $sportText = trim($breadcrumbLists[2]->plaintext);
            // $championsLeagueText = trim($breadcrumbLists[3]->plaintext);

            $sport_title = strtolower($sportText);
            // $league_title = strtolower($championsLeagueText);

            $existingSports = Sport::all();
            $sport = findOrCreateItemSport($existingSports, 'csgo', Sport::class, 52);

            // Найдем все контейнеры
            $lines = $html->find('.lines');
            foreach ($lines as $line) {
                $event = strtolower(trim($line->find('.question-list span', 0)->plaintext));

                if ($event == 'match result' || $event == 'money line') {
                    //Лига
                    $breadcrumbLeague = trim($line->find('.breadcrumb-line', 0)->plaintext);
                    $league_title = strtolower($breadcrumbLeague);
                    $existingLeagues = League::where('sport_id', $sport->id)->get();
                    $league = findOrCreateItemLeagueCsgo($existingLeagues, $league_title, League::class, 52, $sport->id);
                    //Команды
                    $teams = $line->find('[class^=actor-]');
                    $team1 = $teams[0]->plaintext;
                    $team2 = $teams[1]->plaintext;

                    // // Нахождение элемента, содержащего дату и время
                    // $dateElement = $line->find('.date-event', 0);
                    // // Извлечение значения даты и времени
                    // $dateValue = trim($dateElement->find('span', 0)->plaintext);
                    // $timeValue = trim($dateElement->find('span', 1)->plaintext);
                    // // Преобразование значения даты и времени с помощью Carbon
                    // $date = Carbon::createFromFormat('D d M H:i', $dateValue . ' ' . $timeValue)->format('Y-m-d H:i:s');
                    $existingGames = $league->games;
                    $game = findOrCreateItemGameCsgo($existingGames, $team1, $team2, $date ?? now(), Game::class, 52, $league->id);
                    $oddsBox = $line->find('.odds-box-total', 0);
                    if (!empty($oddsBox)) {
                        $elements = $oddsBox->find('span.odd.vertical.centered');

                        if (count($elements) == 3) {
                            $odd_team1 = $elements[0]->find('.container-odd-and-trend', 0)->plaintext;
                            $draw = $elements[1]->find('.container-odd-and-trend', 0)->plaintext;
                            $odd_team2 = $elements[2]->find('.container-odd-and-trend', 0)->plaintext;
                        } elseif (count($elements) == 2) {
                            $odd_team1 = $elements[0]->find('.container-odd-and-trend', 0)->plaintext;
                            $odd_team2 = $elements[1]->find('.container-odd-and-trend', 0)->plaintext;
                            $draw = 0;
                        }

                        if (!$game['reverse']) {
                            Odd::updateOrCreate(['game_id' => $game['item']->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team1, 'draw' => $draw, 'odd_team2' => $odd_team2]);
                        } else {
                            Odd::updateOrCreate(['game_id' => $game['item']->id, 'bookmaker_name' => $bookmaker], ['odd_team1' => $odd_team2, 'draw' => $draw, 'odd_team2' => $odd_team1]);
                        }
                    }
                }
            }
        }
        echo 'mrgreenCsgo';
        $html->clear();
    } catch (Exception $e) {
        // Обработка ошибки
        echo "Произошла ошибка: mrgreenCsgo - $url" . $e->getMessage();
        // Или можете просто проигнорировать ошибку и продолжить выполнение кода дальше
    } finally {
        $driver->quit();
    }
}
