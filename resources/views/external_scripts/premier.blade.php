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

// premier-league
getMatchesSourceLvbet('https://lvbet.lv/sports/en/pre-matches/football/england/premier-league/--/1/35148/37685/');
getMatchesSourceBetsafe('https://www.betsafe.lv/en/sportsbook/football/england/england-premier-league-epl');
getMatchesSourceMrgreen('https://www.mrgreen.lv/en/sports/competition/94-premier-league');
getMatchesSourceOlybet('https://www.olybet.lv/sports?competition=538&game=22910774&region=2570001&type=0&sport=1&');
