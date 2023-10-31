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

function getMatchesSourceMrgreen($url = "https://rollino.io/ca/sport?bt-path=%2Fsoccer%2Fengland%2Fpremier-league-1669818860469096448", $bookmaker = 'mrgreen', $sport = null, $league = null)
{
    // Устанавливаем параметры для подключения к WebDriver
    $host = 'http://localhost:9515'; // Адрес и порт WebDriver сервера

    // Настройки для безголового режима Chrome
    $options = new ChromeOptions();
    $options->addArguments(['--headless', '--disable-gpu', '--no-sandbox']);
    $options->addArguments(['--user-agent=Y	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.36 Edg/116.0.1938.54']);
    $capabilities = DesiredCapabilities::chrome();
    $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);

    // Создаем экземпляр RemoteWebDriver
    $driver = RemoteWebDriver::create($host, $capabilities);

    try {
        $driver->get($url);

        // $wait = new WebDriverWait($driver, 15);
        // $element = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::tagName('magic-sportsbook')));

        // $shadowRoot = $element->getShadowRoot();
        // $iframe = $shadowRoot->findElement(WebDriverBy::className('sportnco-sportsbook'));

        // $driver->switchTo()->frame($iframe);
        $data = $driver->getPageSource();

        if ($data) {
            $html = new simple_html_dom();
            $html->load($data);
            echo $html;
            // Нахождение нужных элементов с помощью CSS-селекторов
            // $breadcrumbLists = $html->find('#breadcrumb-list-drag-scroll-alone ul li');
            // $footballText = trim($breadcrumbLists[1]->plaintext);
            // $championsLeagueText = trim($breadcrumbLists[3]->plaintext);

            // $sport_title = strtolower($footballText);
            // $league_title = strtolower($championsLeagueText);


        }
        echo 'mrgreen';
        $html->clear();
    } catch (Exception $e) {
        // Обработка ошибки
        echo "Произошла ошибка: mrgreen - $url" . $e->getMessage();
        // Или можете просто проигнорировать ошибку и продолжить выполнение кода дальше
    } finally {
        $driver->quit();
    }
}

getMatchesSourceMrgreen();
