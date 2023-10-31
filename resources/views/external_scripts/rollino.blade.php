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
function getMatchesSourceRollino($url, $bookmaker = 'rollino', $sport = null, $league = null)
{
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

    try {
        $driver->get($url);

        $wait = new WebDriverWait($driver, 15);
        $element = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::cssSelector('#bt-inner-page')));

        // $shadowRoot = $element->getShadowRoot();
        // Переключение на Shadow DOM
        $shadowRoot = $driver->executeScript('return arguments[0].shadowRoot', [$element]);

        // Выполнение JavaScript-скрипта для получения содержимого Shadow DOM
        $innerHTML = $driver->executeScript('return arguments[0].innerHTML', [$shadowRoot]);

        // Вывод HTML-кода
        echo $innerHTML;
        // // Переключение на Shadow DOM
        // $shadowRootScript = 'return arguments[0].shadowRoot';
        // $shadowRoot = $driver->executeScript($shadowRootScript, [$element]);

        // // Доступ к элементу внутри Shadow DOM
        // $shadowElement = $shadowRoot->findElements(WebDriverBy::cssSelector('[data-editor-id="eventCardStatusLabel"]'));
        // foreach ($shadowElements as $shadowElement) {
        //     echo $shadowElement->getText() . "\n";
        // }

        // Получение HTML-кода внутри Shadow DOM
        // $innerHTML = $shadowRoot->getAttribute('innerHTML');

        // Вывод HTML-кода
        // print_r($shadowRoot);
        // var_dump($shadowElement);
        // $wait2 = new WebDriverWait($driver, 15);
        // $elements = $wait2->until(WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector('[data-editor-id="eventCardStatusLabel"]')));

        // Вывод содержимого каждого элемента
        // foreach ($elements as $element) {
        //     echo $element->getText() . "\n";
        // }
        $data = $driver->getPageSource();

        if ($data) {
            $html = new simple_html_dom();
            $html->load($data);
            // echo $html;
        }
        echo 'rollino';
        $html->clear();
    } catch (Exception $e) {
        // Обработка ошибки
        echo "Произошла ошибка: rollino - $url" . $e->getMessage();
        // Или можете просто проигнорировать ошибку и продолжить выполнение кода дальше
    } finally {
        $driver->quit();
    }
}
