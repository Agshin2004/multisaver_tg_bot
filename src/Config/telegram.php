<?php

use Telegram\Bot\Api;

$env = parse_ini_file(__DIR__.'/../../.env');

return new Api($env['BOT_API']);
