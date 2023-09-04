<?php
require 'vendor/simpleHtmlDom/simple_html_dom.php';
require 'vendor/autoload.php';

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

function championsLeague()
{
    $process = new Process(['php', '-r', 'require "champions.blade.php";']);
    $process->start();
    $process->wait();
    echo "Результат первого запроса: " . $process->getOutput() . "\n";
    return $process;
}

function premierLeague()
{
    $process = new Process(['php', '-r', 'require "premier.blade.php";']);
    $process->start();
    $process->wait();
    echo "Результат второго запроса: " . $process->getOutput() . "\n";
    return $process;
}


$championsLeague = championsLeague();
$premierLeague = premierLeague();
