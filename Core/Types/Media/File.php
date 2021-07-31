<?php

namespace Phelegram\Core\Types\Media;

use Phelegram\Utilities\Curl;
use Phelegram\Utilities\Env;
use stdClass;

class File
{
    private string $id;
    private string $secret;
    private string $size;
    private string $path;

    public function __construct(stdClass $object)
    {
        $this->id = $object->file_id;
        $this->secret = $object->file_unique_id;
        $this->size = $object->file_size;
        $this->path = $object->file_path;
    }

    public function getID(): string
    {
        return $this->id;
    }

    public function getUniqueID(): string
    {
        return $this->secret;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param fileName fileName to save file into. saves file into id if not passed
     */
    public function download(string $mime, string $fileName = null): bool
    {
        $name = ($fileName ?? $this->getID()).$mime;
        $path = 'https://api.telegram.org/file/bot'.Env::var('TOKEN').'/'.$this->getPath();
        $handle = new Curl();

        return $handle->downloadFromTo($path, $name);
    }
}
