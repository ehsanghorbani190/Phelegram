<?php

namespace Phelegram\Core\Drivers;

use Phelegram\Core\Types\Data\Update;
use Phelegram\Core\Types\Keyboards\Keyboard;
use Phelegram\Core\Types\Media\File;
use Phelegram\Core\Types\Sender\Chat;
use Phelegram\Core\Types\Sender\User;
use Phelegram\Utilities\Curl;
use Phelegram\Utilities\Env;
use RuntimeException;

/**
 * Contains basic actions that every bot in Telegram should be able to do.
 */
class BaseBot
{
    private string $debugID;
    private Curl $request;

    public function __construct()
    {
        $this->debugID = Env::var('DEBUG');
        $this->request = new Curl();
    }

    /**
     * A simple function to check if your token is still valid.
     *
     * @return User Your bot information
     */
    public function getMe(): User
    {
        $res = $this->request->getMethod('getMe');
        if ($res->ok) {
            return new User($res->result);
        }
        $this->debug('Error Code: '.$res->error_code.'. Message: '.$res->description);

        throw new RuntimeException($res->description, $res->error_code);
    }

    /**
     * Get information about a chat.
     *
     * @param string $id ID of the chat that you want information about
     *
     * @return Chat Information about the chat
     */
    public function getChat(string $id): Chat
    {
        $res = $this->request->getMethod('getChat', ['chat_id' => $id]);
        if ($res->ok) {
            return new Chat($res->result);
        }
        $this->debug('Error Code: '.$res->error_code.'. Message: '.$res->description);

        throw new RuntimeException($res->description, $res->error_code);
    }

    /**
     * Getting an update from WebHook, The update will be marked as read.
     *
     * @return Update The update sent to bot from WebHook
     */
    public function getUpdate(): Update
    {
        return new Update(file_get_contents('php://input'));
    }

    /**
     * Getting UNREAD updates and mark them as read. WON'T WORK IF WEBHOOK IS SET.
     *
     * @param int $limit Number of updates to return. Max is 100. Default is 100.
     *
     * @return Update[] Unread Updates
     */
    public function getUpdates(int $limit = 100)
    {
        $res = $this->request->getMethod('getUpdates', ['limit' => ($limit <= 100 && $limit >= 1) ? $limit : 100]);
        $res = $res->result;
        $this->request->getMethod('getUpdates', ['offset' => end($res)->update_id + 1]);
        foreach ($res as $result) {
            yield new Update(json_encode($result));
        }
    }

    /**
     * Send a simple text message to a chat with optional reply_markup(ReplyKeyboard, RemoveReplyKeyboard).
     *
     * @param string   $text     the text to send
     * @param string   $chatId   ID of the chat to send message in ,In case of Channels it would be like @ChannelUserName
     * @param Keyboard $keyboard reply_markup to send, see their description when you create one
     *
     * @return bool shows if message was sent or not
     */
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
        if (!$res->ok) {
            $this->debug('Error Code: '.$res->error_code.'. Message: '.$res->description);
        }

        return $res->ok;
    }

    /**
     * Deletes an existing message from a chat.
     *
     * @param string $chatID    Chat that contains message
     * @param string $messageID ID of the message in chat
     *
     * @return bool shows if message is deleted or not
     */
    public function deleteMessage(string $chatID, string $messageID): bool
    {
        $res = $this->request->getMethod('deleteMessage', [
            'chat_id' => $chatID,
            'message_id' => $messageID,
        ]);
        if (!$res->ok) {
            $this->debug('Error Code: '.$res->error_code.'. Message: '.$res->description);
        }

        return $res->ok;
    }

    /**
     * Forward a message (no matter what type it is).
     *
     * @param string $to        ID of the chat that message'll be forwarded to
     * @param string $from      ID of the chat that contains the message
     * @param string $messageID ID of message that'll be forwarded
     * @param bool   $silent    If true, message'll be sent silently. Users will receive a notification with no sound.
     */
    public function forwardMessage(string $to, string $from, string $messageID, bool $silent = false): bool
    {
        $res = $this->request->getMethod('forwardMessage', ['chat_id' => $to, 'from_chat_id' => $from, 'message_id' => $messageID, 'disable_notification' => $silent]);
        if (!$res->ok) {
            $this->debug('Error Code: '.$res->error_code.'. Message: '.$res->description);
        }

        return $res->ok;
    }

    /**
     * Get info about a file that exists on Telegram clouds.
     *
     * @param string $fileID The file_id (not file_unique_id) returned from Telegram API(in a message)
     *
     * @return File A File Object that can be used to download a file form Telegram
     */
    public function getFile(string $fileID): File
    {
        $fileData = $this->request->getMethod('getFile', ['file_id' => $fileID]);
        if ($fileData->ok) {
            return new File($fileData->result);
        }
        $this->debug('Error Code: '.$fileData->error_code.'. Message: '.$fileData->description);

        throw new RuntimeException($fileData->description, $fileData->error_code);
    }

    /**
     * Stores the update in a json file in case that you want to debug or develop your bot. file name will be the update id.
     *
     * @param Update $update the Update to write in file
     */
    public static function storeInJson(Update $update): bool
    {
        $file = fopen($update->update_id.'.json', 'w');

        return fwrite($file, json_encode($update));
    }

    /**
     * Send a Text to $DEBUG account you added in .env file. It will start with "***DEBUG LOG***". Useful for debugging.
     *
     * @param string $text The text to send
     *
     * @return bool shows if message was sent or not
     */
    public function debug(string $text): bool
    {
        return ('null' != $this->debugID) ? $this->sendMessage(urlencode("***DEBUG LOG*** \n".$text), $this->debugID) : false;
    }
}
