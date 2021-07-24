<?php

namespace Phelegram\Core;

use Phelegram\Core\Types\{
    File,
    Update,
    User\User
};
use Phelegram\Utilities\{
    env,
    Curl
};
//Bot class
class Bot
{
    private $token;
    private $debugID;
    private $request;
    const API = 'https://api.telegram.org/bot';

    public function __construct()
    {
        $this->token = env::var('TOKEN');
        $this->debugID = env::var('DEBUG');
        $this->request = new Curl();
    }

    //main Commands
    public function getMe(): User
    {
        return new User(json_decode($this->request->get($this->method("getMe"))));
    }

    public function getUpdate(): Update
    {
        return new Update($this->request->get("php://input"));
    }
    /**
     * @return Update[]
     */
    public function getUpdates(int $limit = 100)
    {
        $res = json_decode($this->request->get($this->method("getUpdates",["limit" => $limit])));
        $res = $res->result;
        $this->request->get($this->method("getUpdates" , ["offset" => end($res)->update_id + 1]));
        foreach ($res as $result) {
            yield new Update(json_encode($result));
        }
    }
    public function sendMessage(string $text, string $chatId): bool
    {
        $res = $this->request->get($this->method('sendMessage', [
            'chat_id' => $chatId,
            'text' => $text,
        ]));

        return json_decode($res)->ok;
    }
    public function deleteMessage(string $chatID, string $messageID): bool
    {
        return json_decode($this->request->get($this->method("deleteMessage" ,[
            'chat_id' => $chatID,
            'message_id' => $messageID
        ])))->ok;
    }
    public function getFile(string $fileID) : File
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
        return ($this->debugID != "null") ? $this->sendMessage(urlencode("***DEBUG LOG*** \n".$text), $this->debugID) : false;
    }

    //make methods easy to use
    protected function method(string $method, array $params = null): string
    {
        $res = self::API.$this->token.'/'.$method;
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
