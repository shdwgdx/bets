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
        // Ждем загрузки всех элементов на странице 
        $wait = new WebDriverWait($driver, 10);
        $wait->until(WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(
            WebDriverBy::className('game-markets-block')
        ));

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
                    $coefficient  = $oddsContainer->find('.odds__value', 0)->plaintext;

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
        // $wait = new WebDriverWait($driver, 20);
        // $wait->until(function () use ($driver) {
        //     return $driver->executeScript('return document.readyState') === 'complete';
        // });
        // $wait->until(WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(
        //     WebDriverBy::className('lines')
        // ));
        $data = $driver->getPageSource();
        echo $data;
        if ($data) {
            $html = new simple_html_dom();
            $html->load($data);
            // Найдем все контейнеры
            $lines = $html->find('.lines');
            foreach ($lines as $line) {
                $teams = $line->find('[class^=actor-]');
                $team1 = $teams[0]->plaintext;
                $team2 = $teams[1]->plaintext;
                echo $team1;
                echo $team2;
            }
        }
    } finally {
        $driver->quit();
        $html->clear();
    }
}
