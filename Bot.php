<?php
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
    //main Commands
    public function getMe()
    {
        return json_encode(file_get_contents($this->method('getMe')));
    }
    public function getUpdates() 
    {
        return file_get_contents($this->method('getUpdates'));
    }
    //make methods easy to use
    private function method(string $method, array $params = null): string
    {
        $res = $this->api.$method;
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