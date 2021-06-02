<?php

namespace TelegramBot\Core\Types;

use stdClass;

abstract class FromType
{
    private $id;
    private $fName;
    private $lName;
    private $userName;

    public function __construct(stdClass $from)
    {
        $this->id = $from->id;
        $this->fName = $from->first_name;
        if (property_exists($from, 'last_name')) {
            $this->lName = $from->last_name;
        }
        if (property_exists($from, 'username')) {
            $this->userName = $from->username;
        }
    }

    public function getID(): string
    {
        return $this->id;
    }

    public function getFName(): string
    {
        return $this->fName;
    }

    public function getLName(): string
    {
        return $this->lName;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }
}

final class User extends FromType
{
    private $isBot;

    public function __construct(stdClass $user)
    {
        parent::__construct($user);
        $this->isBot = $user->is_bot;
    }

    public function isBot(): bool
    {
        return $this->isBot;
    }
}

final class Chat extends FromType
{
    private $type;
    private $title;

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
