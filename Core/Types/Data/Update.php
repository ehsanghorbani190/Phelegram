<?php

namespace Phelegram\Core\Types\Data;

use stdClass;

class Update
{
    private string $id;
    private Message $message;

    public function __construct(stdClass $update)
    {
        $this->id = $update->update_id;
        $message = $update->message ?? $update->edited_message ?? $update->channel_post ?? $update->edited_channel_post;
        $this->message = (null != $message) ? new Message($message) : null;
    }

    public function getMessage(): ?Message
    {
        return $this->message;
    }

    /**
     * Get the value of id.
     */
    public function getId(): string
    {
        return $this->id;
    }

    public function getSenderID(): string
    {
        return $this->message->getFrom()->getID();
    }

    public function getChatID(): string
    {
        return $this->message->getChat()->getID();
    }
}
