<?php

namespace Phelegram\Core;

use Phelegram\Core\Types\File;
use Phelegram\Core\Types\Keyboards\ReplyKeyboard;
use Phelegram\Core\Types\Update;
use Phelegram\Core\Types\User\User;
use Phelegram\Utilities\Curl;
use Phelegram\Utilities\Env;

//Bot class
class Bot
{
    public const API = 'https://api.telegram.org/bot';
    private $token;
    private $debugID;
    private $request;

    public function __construct()
    {
        $this->token = Env::var('TOKEN');
        $this->debugID = Env::var('DEBUG');
        $this->request = new Curl();
    }

    //main Commands
    public function getMe(): User
    {
        return new User(json_decode($this->request->get($this->method('getMe'))));
    }

    public function getUpdate(): Update
    {
        return new Update(file_get_contents('php://input'));
    }

    /**
     * @return Update[]
     */
    public function getUpdates(int $limit = 100)
    {
        $res = json_decode($this->request->get($this->method('getUpdates', ['limit' => $limit])));
        $res = $res->result;
        $this->request->get($this->method('getUpdates', ['offset' => end($res)->update_id + 1]));
        foreach ($res as $result) {
            yield new Update(json_encode($result));
        }
    }

    public function sendMessage(string $text, string $chatId, ReplyKeyboard $keyboard = null): bool
    {
        $options = [
            'chat_id' => $chatId,
            'text' => $text,
        ];
        if (null != $keyboard) {
            $options['reply_markup'] = $keyboard;
        }
        $res = $this->request->get($this->method('sendMessage', $options));

        return json_decode($res)->ok;
    }

    public function deleteMessage(string $chatID, string $messageID): bool
    {
        return json_decode($this->request->get($this->method('deleteMessage', [
            'chat_id' => $chatID,
            'message_id' => $messageID,
        ])))->ok;
    }

    public function getFile(string $fileID): File
    {
        $fileData = json_decode($this->request->get($this->method('getFile', ['file_id' => $fileID])));

        return new File($fileData->result);
    }

    //testing functions
    public static function storeInJson(Update $update): bool
    {
        $file = fopen($update->update_id.'.json', 'w');

        return fwrite($file, json_encode($update));
    }

    public function debug(string $text): bool
    {
        return ('null' != $this->debugID) ? $this->sendMessage(urlencode("***DEBUG LOG*** \n".$text), $this->debugID) : false;
    }

    //make methods easy to use
    protected function method(string $method, array $params = null): string
    {
        $res = self::API.$this->token.'/'.$method;
        if (!empty($params)) {
            $res .= '?';
            foreach ($params as $param => $value) {
                $res .= trim($param).'='.((is_string($value)) ? trim($value) : json_encode($value));
                if (array_key_last($params) != $param) {
                    $res .= '&';
                }
            }
        }

        return $res;
    }
}
