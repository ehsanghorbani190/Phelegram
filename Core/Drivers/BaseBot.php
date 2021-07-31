<?php

namespace Phelegram\Core\Drivers;

use Phelegram\Core\Types\Data\Update;
use Phelegram\Core\Types\Keyboards\Keyboard;
use Phelegram\Core\Types\Media\File;
use Phelegram\Core\Types\Sender\User;
use Phelegram\Utilities\Curl;
use Phelegram\Utilities\Env;

class BaseBot
{
    private string $debugID;
    private Curl $request;

    public function __construct()
    {
        $this->debugID = Env::var('DEBUG');
        $this->request = new Curl();
    }

    public function getMe(): User
    {
        return new User(json_decode($this->request->getMethod('getMe')));
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
        $res = json_decode($this->request->getMethod('getUpdates', ['limit' => $limit]));
        $res = $res->result;
        $this->request->getMethod('getUpdates', ['offset' => end($res)->update_id + 1]);
        foreach ($res as $result) {
            yield new Update(json_encode($result));
        }
    }

    public function sendMessage(string $text, string $chatId, Keyboard $keyboard = null): bool
    {
        $options = [
            'chat_id' => $chatId,
            'text' => $text,
        ];
        if (null != $keyboard) {
            $options['reply_markup'] = $keyboard;
        }
        $res = $this->request->getMethod('sendMessage', $options);

        return json_decode($res)->ok;
    }

    public function deleteMessage(string $chatID, string $messageID): bool
    {
        return json_decode($this->request->getMethod('deleteMessage', [
            'chat_id' => $chatID,
            'message_id' => $messageID,
        ]))->ok;
    }

    public function getFile(string $fileID): File
    {
        $fileData = json_decode($this->request->getMethod('getFile', ['file_id' => $fileID]));

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
}
