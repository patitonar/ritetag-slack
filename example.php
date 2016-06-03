<?php

require_once 'vendor/autoload.php';
require_once 'config.php';

$command = isset($_POST['command']) ? $_POST['command'] : "";
$text    = isset($_POST['text'])    ? $_POST['text']    : "";

$handler = new \Ritetag\API\CommandHandler();
$reply = $handler->processCommand($command,$text);

header('Content-type: application/json');
echo $reply;
