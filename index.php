<?php
//headers
header('Content-Type: application/json');

//Constants
define('TOKEN', '1767056535:AAHluR8Kblu_bg9MR67ztsVE26Vdzc7GMI8');

require_once(__DIR__ ."/Bot.php");
//main executable program 
$bot = new Bot(TOKEN,true);
$update = $bot->getUpdates();
$bot->sendMessage($update->message->text , $update->message->chat->id);