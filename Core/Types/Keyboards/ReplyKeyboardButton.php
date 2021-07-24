<?php

namespace Phelegram\Core\Types\Keyboards;

class ReplyKeyboardButton
{
    public string $text;
    public $request_contact = false;
    public $request_location = false;
    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function requestContact(): self
    {
        $this->request_contact = true;

        return $this;
    }

    public function requestLocation(): self
    {
        $this->request_location = true;

        return $this;
    }
}
