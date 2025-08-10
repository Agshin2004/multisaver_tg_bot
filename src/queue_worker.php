<?php

require __DIR__ . '/../vendor/autoload.php';


$queueFilePath = base_path('src/Storage/queue.json');
$telegram = require base_path('src/Config/telegram.php');

while (true) {
    $queue = file_exists($queueFilePath) ? json_decode(file_get_contents($queueFilePath), true) : [];
    $tgService = new \Services\TelegramService($telegram);

    if (empty($queue)) {
        echo "queue is empty...\n";
        sleep(5);
        continue;
    } else {
        echo 'queue is not empty... ';
    }

    // get first url in the queue and delete it
    $task = array_shift($queue);

    // saving queue back without taken url
    file_put_contents($queueFilePath, json_encode($queue, JSON_PRETTY_PRINT));

    $url = $task['url'];
    $chatId = $task['chat_id'];

    try {
        $filePath = downloadSoundcloud($url);

        $tgService->sendAudio((int)$chatId, $filePath);

        unlink($filePath);
    } catch (\Exception $e) {
        // TODO: send error message if was not successful
        $tgService->sendText($chatId, "Something went wrong while downloading your file, try again");
        $tgService->sendText($chatId, $e->getMessage());
    }
}