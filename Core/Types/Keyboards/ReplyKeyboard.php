<?php

namespace Phelegram\Core\Types\Keyboards;


class ReplyKeyboard implements Keyboard
{
    private $keyboard = [];
    private $resize_keyboard = false;
    private $one_time_keyboard = false;
    private $selective = false;
    private $input_field_placeholder;

    /**
     * A Keyboard that replies data and makes user interaction easier.
     *
     * @param string $placeholder will appear in the inputField while using keyboard
     */
    public function __construct(string $placeholder = null)
    {
        if (null != $placeholder) {
            $this->input_field_placeholder = $placeholder;
        }
    }

    /**
     * If set, Telegram will try to resize the row for best fit.
     */
    public function setResizable(): self
    {
        $this->resize_keyboard = true;

        return $this;
    }

    /**
     * If set, the keyboard will hide after use.
     */
    public function setOnceOnly(): self
    {
        $this->one_time_keyboard = true;

        return $this;
    }

    /**
     * If set, the keyboard will hide only for user that used it.
     */
    public function setSelective(): self
    {
        $this->selective = true;

        return $this;
    }

    /**
     * Add a simple text button in the specified row. Returns the text on click as a message.
     *
     * @param string $text Label of button
     * @param int    $row  Row to insert button into, starts from 0, default is set to 0
     */
    public function addButton(string $text, int $row = 0): self
    {
        $this->keyboard[$row][] = ['text' => $text, 'request_contact' => false, 'request_location' => false];

        return $this;
    }

    /**
     * Adds a button that sends the user's Contact on click, in the specified row. ONLY FOR PRIVATE CHATS.
     *
     * @param string $text Label of button
     * @param int    $row  Row to insert button into, starts from 0, default is set to 0
     */
    public function addContactButton(string $text, int $row = 0): self
    {
        $this->keyboard[$row][] = ['text' => $text, 'request_contact' => true, 'request_location' => false];

        return $this;
    }

    /**
     * Adds a button that sends the user's Location on click, in the specified row. ONLY FOR PRIVATE CHATS.
     *
     * @param string $text Label of button
     * @param int    $row  Row to insert button into, starts from 0, default is set to 0
     */
    public function addLocationButton(string $text, int $row = 0): self
    {
        $this->keyboard[$row][] = ['text' => $text, 'request_contact' => false, 'request_location' => true];

        return $this;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
