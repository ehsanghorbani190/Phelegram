<?php
namespace Phelegram\Core\Types;

use Phelegram\Core\Types\Message;

class Update
{
    private $id;
    private Message $message;
    public function __construct(string $update)
    {
        $update = json_decode($update);
        $this->id = $update->update_id;
        $message = $update->message ?? $update->edited_message ?? $update->channel_post ?? $update->edited_channel_post;
        $this->message =($message != null) ? new Message($message): null;
    }

    public function getMessage() : ?Message
    {
        return $this->message;
    }

    /**
     * Get the value of id
     */
    public function getId() :string
    {
        return $this->id;
    }
}
