<?php


namespace Handlers;

class DownloadHandler
{
//    TODO: Take downloading logic and write that here
    public static $name;

    public static function setName(string $name): void
    {
        self::$name = $name;
    }
}