<?php
require 'vendor/simpleHtmlDom/simple_html_dom.php';
require 'vendor/autoload.php';

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

function betsafePageProcess($url)
{
    $process = new Process(['php', '-r', 'require "betsafe.php"; getPageSource("' . $url . '");']);
    $process->start();
    return $process;
}

function lvbetPageProcess($url)
{
    $process = new Process(['php', '-r', 'require "lvbet.php"; getPageSource("' . $url . '");']);
    $process->start();
    return $process;
}

function betsafeMatchesProcess($url)
{
    $process = new Process(['php', '-r', 'require "betsafe.php"; getMatchesSource("' . $url . '");']);
    $process->start();
    return $process;
}

function lvbetMatchesProcess($url)
{
    $process = new Process(['php', '-r', 'require "lvbet.php"; getMatchesSource("' . $url . '");']);
    $process->start();
    return $process;
}

function mrgreenMatchesProcess($url)
{
    $process = new Process(['php', '-r', 'require "mrgreen.php"; getMatchesSource("' . $url . '");']);
    $process->start();
    return $process;
}

function olybetMatchesProcess($url)
{
    $process = new Process(['php', '-r', 'require "olybet.php"; getMatchesSource("' . $url . '");']);
    $process->start();
    return $process;
}

function pafbetMatchesProcess($url)
{
    $process = new Process(['php', '-r', 'require "pafbet.php"; getMatchesSource("' . $url . '");']);
    $process->start();
    return $process;
}

// $betsafeMatches = betsafeMatchesProcess('https://www.betsafe.lv/en/sportsbook/football/champions-league/champions-league');
$lvbetMatches = lvbetMatchesProcess('https://lvbet.lv/sports/en/pre-matches/multiple--?leagues=57921');
// $mrgreenMatches = mrgreenMatchesProcess('https://www.mrgreen.lv/en/sports/competition/6674-champions-league');
// не грузит $olybetMatches = olybetMatchesProcess('https://www.olybet.lv/sports?competition=18286520&game=22912934&region=20001&type=0&sport=1&');
// $pafbetMatches = pafbetMatchesProcess('https://www.pafbet.lv/ru/betting#/sports-hub/football/champions_league_qualification');


// $betsafeMatches->wait();
$lvbetMatches->wait();
// $mrgreenMatches->wait();
// $olybetMatches->wait();
// $pafbetMatches->wait();

// echo "Результат первого запроса: " . $betsafeMatches->getOutput() . "\n";
echo "Результат второго запроса: " . $lvbetMatches->getOutput() . "\n";
// echo "Результат третьего запроса: " . $mrgreenMatches->getOutput() . "\n";
// echo "Результат второго запроса: " . $olybetMatches->getOutput() . "\n";
// echo "Результат второго запроса: " . $pafbetMatches->getOutput() . "\n";

// $betsafeMatch = betsafePageProcess('https://www.betsafe.lv/ru/stavki/futbol/liga-chempionov/liga-chempionov?eventId=f-PTp3lr3Lg0qGWqVoQb0W6Q&eti=0&fs=true');
// $lvbetMatch = lvbetPageProcess('https://lvbet.lv/sports/ru/pre-matches/%D1%84%D1%83%D1%82%D0%B1%D0%BE%D0%BB/%D0%B5%D0%B2%D1%80%D0%BE%D0%BF%D0%B0/uefa-champions-league-qualification/galatasaray-vs-%D0%BC%D0%BE%D0%BB%D0%B4%D0%B5-%D1%84%D0%BA/--/1/35259/57921/bc:22910777/');


// $betsafeMatch->wait();
// $lvbetMatch->wait();

// echo "Результат первого запроса: " . $betsafeMatch->getOutput() . "\n";
// echo "Результат второго запроса: " . $lvbetMatch->getOutput() . "\n";
