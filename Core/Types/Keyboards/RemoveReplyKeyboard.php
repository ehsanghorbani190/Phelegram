<?php

namespace Phelegram\Core\Types\Keyboards;


class RemoveReplyKeyboard implements Keyboard
{
    private bool $selective;
    private bool $remove_keyboard;

    /**
     * Removes the current ReplyKeyboard.
     *
     * @param bool $selective If set, Removes the keyboard for the users who used it
     */
    public function __construct(bool $selective = false)
    {
        $this->remove_keyboard = true;
        $this->selective = $selective;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
