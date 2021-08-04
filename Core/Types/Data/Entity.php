<?php

namespace Phelegram\Core\Types\Data;

use Phelegram\Core\Types\Sender\User;
use stdClass;

class Entity
{
    private string $type;
    private int $offset;
    private int $length;
    private string $url;
    private stdClass $user;
    private string $language;
    private string $text;

    public function __construct(stdClass $entity, string $messageText)
    {
        foreach ($entity as $key => $value) {
            $this->{$key} = $value;
        }
        $this->text = substr($messageText, $this->offset, $this->length);
    }

    /**
     * Get entity type. Possible values: “mention” (@username), “hashtag” (#hashtag), “cashtag” ($USD), “bot_command” (/start@jobs_bot), “url” (https://telegram.org), “email” (do-not-reply@telegram.org), “phone_number” (+1-212-555-0123), “bold” (bold text), “italic” (italic text), “underline” (underlined text), “strikethrough” (strikethrough text), “code” (monowidth string), “pre” (monowidth block), “text_link” (for clickable text URLs), “text_mention” (for users without usernames).
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Offset in UTF-16 code units to the start of the entity.
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * Get length of the entity in UTF-16 code units.
     */
    public function getLength(): int
    {
        return $this->length;
    }

    /**
     * For “text_link” only, url that will be opened after user taps on the text.
     *
     * @return string
     */
    public function getUrl(): ?string
    {
        return isset($this->url) ? $this->url : null;
    }

    /**
     * For “text_mention” only, the mentioned user.
     *
     * @return User
     */
    public function getUser(): ?User
    {
        return isset($this->user) ? new User($this->user) : null;
    }

    /**
     * For “pre” only, the programming language of the entity text.
     */
    public function getLanguage(): ?string
    {
        return isset($this->language) ? $this->language : null;
    }

    /**
     * Get entity's text.
     */
    public function getText(): string
    {
        return $this->text;
    }
}
