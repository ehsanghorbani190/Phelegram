<?php
namespace Bot;
use env\env;
use Types\Update\Update;
use Types\File\File;
//Bot class
class Bot
{
    private $token;
    private $useWebhook;
    private $debugID;

    public function __construct(string $debugID = null , bool $useWebhook = true)
    {
        $this->token = env::var("TOKEN");
        define('API', 'https://api.telegram.org/bot'.$this->token.'/');
        $this->useWebhook = $useWebhook;
        if (null !== $debugID) {
            $this->debugID = $debugID;
        }
    }

    //main Commands
    public function getMe(): Update
    {
        return json_decode(file_get_contents($this->method('getMe')));
    }

    public function getUpdates(): Update
    {
        $destination = ($this->useWebhook) ? 'php://input' : $this->method('getUpdates');

        return json_decode(file_get_contents($destination));
    }

    public function sendMessage(string $text, string $chatId): bool
    {
        $res = file_get_contents($this->method('sendMessage', [
            'chat_id' => $chatId,
            'text' => $text,
        ]));

        return (false != $res) ? true : false;
    }

    public function getFile(string $fileID, string $fileName)
    {
        $fileData = json_decode(file_get_contents($this->method('getFile', ['file_id' => $fileID])));
        $file = new File($fileData);
        $file->download();
    }
    public function sendPhotoByID(string $fileID , string $chatID , string $caption = '') : Update
    {
        return json_decode(file_get_contents($this->method('sendPhoto', [
            'chat_id' => $chatID,
            'photo' => $fileID,
            'caption' => $caption
        ])));
    }
    //testing functions
    public static function storeInJson(Update $update): bool
    {
        $file = fopen($update->update_id.'.json', 'w');

        return fwrite($file, json_encode($update));
    }

    public function debug(string $text): bool
    {
        return $this->sendMessage("***DEBUG LOG***".PHP_EOL.$text, $this->debugID);
    }
    public function setDebugID(string $debugID){
        $this->debugID = $debugID;
    }
    //make methods easy to use
    private function method(string $method, array $params = null): string
    {
        $res = API.$method;
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
}
