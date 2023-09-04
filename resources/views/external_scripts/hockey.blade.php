<?php
require 'vendor/simpleHtmlDom/simple_html_dom.php';
require 'vendor/autoload.php'; // Подключение php-webdriver
require_once __DIR__ . '/../../../vendor/autoload.php';

$app = require_once __DIR__ . '/../../../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

require 'methods.blade.php';
require 'betsafe.blade.php';
require 'lvbet.blade.php';
require 'mrgreen.blade.php';
require 'olybet.blade.php';

getMatchesSourceBetsafe('https://www.betsafe.lv/en/sportsbook/ice-hockey/nhl/nhl');
getMatchesSourceLvbet('https://lvbet.lv/sports/en/pre-matches/ice-hockey/north-america/nhl/--/2/34642/35109/');
getMatchesSourceMrgreen('https://www.mrgreen.lv/en/sports/competition/6-nhl');
// getMatchesSourceOlybet('https://www.olybet.lv/sports?competition=18286520&game=22912934&region=20001&type=0&sport=1&lang=en');
