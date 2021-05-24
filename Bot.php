<?php
//Bot class
final class Bot
{
    private $token;
    private $api;
    protected $useWebhook;

    public function __construct(string $TOKEN, bool $useWebhook = false)
    {
        $this->token = $TOKEN;
        $this->api = 'https://api.telegram.org/bot'.$TOKEN.'/';
        $this->useWebhook = $useWebhook;
    }
    //main Commands
    public function getMe() :stdClass
    {
        return json_decode(file_get_contents($this->method('getMe')));
    }
    public function getUpdates() :stdClass
    {
        $destination = ($this->useWebhook) ? "php://input" :$this->method('getUpdates');
        return json_decode(file_get_contents($destination));
    }
    public function sendMessage(string $text , string $chatId) : bool 
    {
        $res = file_get_contents($this->method('sendMessage' , [
            'chat_id' => $chatId,
            'text' => $text
        ]));
        return ($res != false) ? true : false;
    }
    public function getFile(string $fileID , string $fileName){
        $fileData = json_decode(file_get_contents($this->method("getFile" , ['file_id' => $fileID])));
        copy("https://api.telegram.org/file/bot".$this->token."/". $fileData->result->file_path, __DIR__."/".$fileName);
    }
    //make methods easy to use
    private function method(string $method, array $params = null): string
    {
        $res = $this->api.$method;
        if (!empty($params)) {
            $res .= '?';
            foreach ($params as $param => $value) {
                $res .= trim($param).'='.trim($value);
                if (array_key_last($params) != $param) {
                    $res .= '&';
                }
            }
        }
        return $res;
    }
    //testing functions 
    public static function storeInJson(stdClass $update) : bool{
        $file = fopen($update->update_id.".json" , 'w');
        $res = fwrite($file,json_encode($update));
        return $res;
    }
}