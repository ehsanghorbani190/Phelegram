<?php
namespace Phelegram\Core\Types;

use Phelegram\Core\Types\Message;
class Update{
    private $id;
    public function __construct(string $update) {
        $update = json_decode($update);
        $this->id = $update->update_id;
        unset($update->update_id);
        foreach($update as $key=>$value) $this->{$key} = $value;
    }

    public function getMessage() : ?Message
    {
        return (isset($this->message)) ? new Message($this->message) : null;
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }
}