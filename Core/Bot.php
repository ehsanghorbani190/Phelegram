<?php

namespace TelegramBot\Core;

use TelegramBot\Core\Types\File;
use TelegramBot\Core\Types\Update;
use TelegramBot\Utilities\env;
use TelegramBot\Utilities\Curl;
use TelegramBot\Utilities\Sql;
//Bot class
class Bot
{
    private $token;
    private $debugID;
    private $request;

    public function __construct()
    {
        $this->token = env::var('TOKEN');
        define('API', 'https://api.telegram.org/bot'.$this->token.'/');
        $this->debugID = env::var('DEBUG');
        $this->request = new Curl();
    }

    //main Commands
    public function getMe(): Update
    {
        return json_decode($this->request->get($this->method("getMe")));
    }

    public function getUpdateFromWebhook(): Update
    {
        return json_decode($this->request->get("php://input"));
    }

    public function getUpdates(int $limit = null) : array
    {
        $sql = new Sql();
        $last = end($sql->selectFieldsFrom("Updates"))['id'];
        $options = [];
        if($last != null) $options["offset"] =  $last + 1;
        if($limit != null) $options['limit'] = $limit;
        $res = json_decode($this->request->get($this->method("getUpdates", $options)));
        $res = $res->result;
        $id = end($res)->update_id;
        $sql->InsertInto('Updates' , ['id' => $id]);
        // $updates = [];
        // foreach ($res as $result) {
        //     $updates[] = new Update($result);
        // }
        return $res;
    }
    public function sendMessage(string $text, string $chatId): bool
    {
        $res = $this->request->get($this->method('sendMessage', [
            'chat_id' => $chatId,
            'text' => $text,
        ]));

        return (false != $res) ? true : false;
    }

    public function getFile(string $fileID, string $fileName)
    {
        $fileData = json_decode($this->request->get($this->method('getFile', ['file_id' => $fileID])));
        $file = new File($fileData);
        $file->download();
    }

    public function sendPhotoByID(string $fileID, string $chatID, string $caption = ''): Update
    {
        return json_decode($this->request->get($this->method('sendPhoto', [
            'chat_id' => $chatID,
            'photo' => $fileID,
            'caption' => $caption,
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
        return ($this->debugID != "null") ? $this->sendMessage('***DEBUG LOG***'.chr(10).$text, $this->debugID) : false;
    }

    public function setDebugID(string $debugID)
    {
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
