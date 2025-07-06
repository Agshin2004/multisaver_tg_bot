<?php

use Telegram\Bot\FileUpload\InputFile;

require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/config.php';

$myData = $telegram->getMe();

$offset = 0;
while (true) {
    $updates = $telegram->getUpdates([
        'offset' => $offset,
        'timeout' => 30, // long-polling timeout
    ]);

    print_r($updates);

    foreach ($updates as $update) {
        $updateId = $update->updateId;
        $message = $update->getMessage();

        if ($message) {
            $chatId = $message->getChat()->getId();
            $text = $message->getText();

            $telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => $text,
            ]);

            $telegram->sendPhoto([
                'chat_id' => $chatId,
                'photo' => InputFile::create('https://castellobranco.sc.gov.br/uploads/sites/468/2022/05/676418_0.672772001304352435_imagem_030.jpg'),
            ]);
        }
        $offset = $updateId + 1; // last id + 1 so it will send old messages
    }
    sleep(1);
}
