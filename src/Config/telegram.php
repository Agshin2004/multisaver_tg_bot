<?php

namespace Config;

use Telegram\Bot\Api;


$env = parse_ini_file(__DIR__.'/../../.env');

try {
    return new Api($env['BOT_API']);
} catch (\Telegram\Bot\Exceptions\TelegramSDKException) {
    echo 'Token is not valid';
    die();
}
