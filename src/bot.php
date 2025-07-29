<?php

require __DIR__ . '/../vendor/autoload.php';

use Handlers\MessageHandler;
use Services\TelegramService;

// bootstrap the app
$telegram = require __DIR__ . '/config/telegram.php';
$tgService = new TelegramService($telegram);
$handler = new MessageHandler($tgService);

// persisting offset; decided to move into own file for clarity
$offsetFile = base_path('src/Storage/offset.txt');
$offset = getLatestOffset($tgService);

while (true) {
    $updates = $tgService->getUpdates($offset);

    if (empty($updates)) {
        echo "No updates...\n";
    }

    foreach ($updates as $update) {
        print_r($update);

        $updateId = $update->updateId;
        $message = $update->getMessage();

        if ($message) {
            $handler->handle($message);
        }

        $offset = $updateId + 1;
        file_put_contents($offsetFile, $offset);
    }

    sleep(1);
}
