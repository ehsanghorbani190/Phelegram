<?php
namespace Phelegram\Core\Types\User;

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