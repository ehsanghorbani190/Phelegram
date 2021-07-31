<?php

namespace Phelegram\Core\Types\Sender;

use stdClass;

final class User extends Credentials
{
    private bool $isBot;

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
