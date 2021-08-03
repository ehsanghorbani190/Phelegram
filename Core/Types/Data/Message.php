<?php

namespace Phelegram\Core\Types\Data;

use Phelegram\Core\Types\Sender\Chat;
use Phelegram\Core\Types\Sender\User;
use stdClass;

final class Message
{
    private string $id;
    private string $date;
    private Chat $chat;

    public function __construct(stdClass $message)
    {
        $this->id = $message->message_id;
        $this->chat = new Chat($message->chat);
        $this->date = gmdate('Y-m-d H:i:s', $message->date);
        unset($message->message_id, $message->chat, $message->from, $message->date);

        foreach ($message as $key => $value) {
            $this->{$key} = $value;
        }
    }

    /**
     * Get the value of from.
     */
    public function getFrom(): ?User
    {
        return isset($this->from) ? new User($this->from) : null;
    }

    /**
     * Get the value of chat.
     */
    public function getChat(): Chat
    {
        return $this->chat;
    }

    /**
     * Get the value of date.
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * Get the value of id.
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Get text for text messages.
     */
    public function getText(): ?string
    {
        return $this->text ?? null;
    }

    /**
     * Get caption for multimedia messages.
     */
    public function getCaption(): ?string
    {
        return $this->caption ?? null;
    }
}
