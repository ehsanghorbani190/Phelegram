<?php
use Core\Bot;
//main executable program 
$bot = new Bot();
$update = $bot->getUpdates();
$bot->sendMessage('HELLO' , $update->message->chat->id);