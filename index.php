<?php
//headers
header('Content-Type: application/json');

//Constants
define('TOKEN', '1767056535:AAHluR8Kblu_bg9MR67ztsVE26Vdzc7GMI8');

//Bot class
final class Bot
{
    private $token;
    private $api;

    public function __construct(string $TOKEN)
    {
        $this->token = $TOKEN;
        $this->api = 'https://api.telegram.org/bot'.$TOKEN.'/';
    }

    public function getMe()
    {
        return json_encode(file_get_contents($this->command('getMe')));
    }

    //make commands easy to use
    private function command(string $command, array $params = null): string
    {
        $res = $this->api.$command;
        if (!empty($params)) {
            $res .= '?';
            foreach ($params as $param => $value) {
                $res .= $param.'='.$value;
                if (array_key_last($params) != $param) {
                    $res .= '&';
                }
            }
        }
        return $res;
    }
}

//main executable program 
$bot = new Bot(TOKEN);
echo $bot->getMe();
