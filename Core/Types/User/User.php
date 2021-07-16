<?php

namespace Phelegram\Core\Types\User;

use stdClass;


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

