<?php
require 'vendor/simpleHtmlDom/simple_html_dom.php';
require 'vendor/autoload.php'; // Подключение php-webdriver
require_once __DIR__ . '/../../../vendor/autoload.php';

$app = require_once __DIR__ . '/../../../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

require 'methods.blade.php';
require 'betsafe.blade.php';
require './csgo/betsafe.blade.php';
require './csgo/lvbet.blade.php';
require './csgo/mrgreen.blade.php';
require 'lvbet.blade.php';
require 'mrgreen.blade.php';
require 'olybet.blade.php';

//champions-league
getMatchesSourceLvbet('https://lvbet.lv/sports/en/pre-matches/football/europe/uefa-champions-league-qualification/--/1/35259/57921/');
getMatchesSourceBetsafe('https://www.betsafe.lv/en/sportsbook/football/champions-league/champions-league');
getMatchesSourceMrgreen('https://www.mrgreen.lv/en/sports/competition/6674-champions-league');
getMatchesSourceOlybet('https://www.olybet.lv/sports?competition=18286520&game=22912934&region=20001&type=0&sport=1&lang=en');
