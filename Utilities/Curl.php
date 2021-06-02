<?php

namespace TelegramBot\Utilities;

use Exception;

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

    public function downloadFromTo(string $url, string $name, array $options = null): ?string
    {
        try {
            curl_setopt($this->handle, CURLOPT_URL, $url);
            curl_setopt($this->hanlde, CURLOPT_RETURNTRANSFER, 1);
            $file = fopen(dirname(__DIR__).'/Storage/'.$name, 'w+');
            if (!$file) {
                throw new Exception('Cant open new file: ' . $name );
            }
            curl_setopt($this->handle, CURLOPT_FILE, $file);
            curl_setopt($this->handle, CURLOPT_TIMEOUT, 15);
            if (!empty($options)) {
                curl_setopt_array($this->handle, $options);
            }
            $result = curl_exec($this->handle);
            curl_reset($this->handle);

            return ($result != false) ? $result : null;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
