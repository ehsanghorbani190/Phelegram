<?php

namespace Phelegram\Core\Types\Sender;

use stdClass;

abstract class Credentials
{
    private string $id;
    private string $fName;
    private string $lName;
    private string $userName;

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

    public function getLName(): ?string
    {
        return $this->lName ?? null;
    }

    public function getUserName(): ?string
    {
        return $this->userName ?? null;
    }
}
