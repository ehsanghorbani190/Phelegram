<?php

namespace Core\Types;

use Utilities\env;
use stdClass;

class File
{
    private $id;
    private $secret;
    private $size;
    private $path;

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
    public function download(string $fileName = null)
    {
        $name = (null !== $fileName) ? $fileName : $this->getID();
        copy('https://api.telegram.org/file/bot'.env::var('TOKEN').'/'.$this->getPath(), dirname(__DIR__).'/Storage/'.$name);
    }
}
