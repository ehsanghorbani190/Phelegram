<?php

namespace Phelegram\Core\Types\Sender;

use stdClass;

final class Chat extends Credentials
{
    private string $type;
    private string $title;

    public function __construct(stdClass $chat)
    {
        parent::__construct($chat);
        if (property_exists($chat, 'type')) {
            $this->type = $chat->type;
        }
        if (property_exists($chat, 'title')) {
            $this->title = $chat->title;
        }
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
