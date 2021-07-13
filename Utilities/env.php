<?php
namespace Phelegram\Utilities;

final class env
{
    public static function var(string $varName): string
    {
        $envFile = fopen(dirname(__DIR__).'/.env', 'r');
        if (!$envFile) {
            return 'env file not found';
        }
        while (!feof($envFile)) {
            $line = fgets($envFile);
            if (false !== strstr($line, '$'.ucwords(trim($varName)))) {
                $var =  explode('=', $line)[1];
                return str_replace("\n" , '' , $var);
            }
        }

        return 'No var found with name'.$varName;
    }
}
