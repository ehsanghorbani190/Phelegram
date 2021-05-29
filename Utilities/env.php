<?php
namespace Utilities;

final class env
{
    public static function var(string $varName): string
    {
        $envFile = fopen('./../.env', 'r');
        if (!$envFile) {
            return 'env file not found';
        }
        while (!feof($envFile)) {
            $line = fgets($envFile);
            if (false !== strstr($line, '$'.ucwords(trim($varName)))) {
                return explode('=', $line)[1];
            }
        }

        return 'No var found with name'.$varName;
    }
}
