<?php

require __DIR__ . '/../vendor/autoload.php';

use Handlers\MessageHandler;
use Services\TelegramService;

// bootstrap the app
//$telegram = require __DIR__ . '/config/telegram.php';
$telegram = require base_path('src/Config/telegram.php'); // __DIR__ . '/config/telegram.php';
$tgService = new TelegramService($telegram);
$handler = new MessageHandler($tgService);

// persisting offset; decided to move into own file for clarity
$offsetFile = base_path('src/Storage/offset.txt');
$offset = getStoredOffset($offsetFile);


while (true) {
    $updates = $tgService->getUpdates($offset);

    if (empty($updates)) {
        echo "No updates...\n";
    }

    foreach ($updates as $update) {
        // print_r($update);

        $updateId = $update->updateId; // updateid is essentially offset
        $message = $update->getMessage();

        if ($message) {
            $message = $update->getMessage();
            if ($message instanceof \Telegram\Bot\Objects\Message) {
                $handler->handle($message);
            }
        }

        $offset = $updateId + 1;
        file_put_contents($offsetFile, $offset);
    }

    sleep(1);
}
