<?php

namespace Phelegram\Utilities;

use Exception;
use InvalidArgumentException;

final class Curl
{
    private $handle;

    public function __construct()
    {
        $this->handle = curl_init();
    }

    public function get(string $url, array $options = null): string
    {
        try {
            curl_setopt($this->handle, CURLOPT_URL, $url);
            curl_setopt($this->handle, CURLOPT_RETURNTRANSFER, 1);
            if (!empty($options)) {
                curl_setopt_array($this->handle, $options);
            }
            $result = curl_exec($this->handle);
            curl_reset($this->handle);

            return $result;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function post(string $url, array $options = null): string
    {
        try {
            curl_setopt($this->handle, CURLOPT_URL, $url);
            curl_setopt($this->hanlde, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($this->handle, CURLOPT_POST, true);
            if (!empty($options)) {
                curl_setopt_array($this->handle, $options);
            }
            $result = curl_exec($this->handle);
            curl_reset($this->handle);

            return $result;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function downloadFromTo(string $url, string $name, array $options = null): bool
    {
        try {
            curl_setopt($this->handle, CURLOPT_URL, $url);
            curl_setopt($this->handle, CURLOPT_RETURNTRANSFER, 1);
            $file = fopen(dirname(__DIR__).'/Storage/'.$name, 'w+');
            if (!$file) {
                throw new Exception('Cant open new file: '.$name);
            }
            curl_setopt($this->handle, CURLOPT_FILE, $file);
            curl_setopt($this->handle, CURLOPT_TIMEOUT, 15);
            if (!empty($options)) {
                curl_setopt_array($this->handle, $options);
            }
            $result = curl_exec($this->handle);
            $status = curl_getinfo($this->handle, CURLINFO_HTTP_CODE);
            curl_reset($this->handle);
            if (200 == $status) {
                return true;
            }

            throw new InvalidArgumentException('Error while downloading file');
        } catch (Exception $e) {
            echo $e->getMessage().$e->getTraceAsString();

            return false;
        }
    }
}
