<?php

use Services\TelegramService;

function updateOffset(&$offset, $updateId)
{
    $offset = $updateId + 1; // last id + 1 so it will not send old messages
}

function getLatestOffset(TelegramService $tgService): int
{
    // get only the latest update (1 message) regardless of offset to find the most recent update id
    $updates = $tgService->getUpdates(0, 1); // offset - 0 (all messages); 1 (limit) gets LATEST 1 message
    $offset = null;

    if (!empty($updates)) {
        $lastUpdate = end($updates);
        $offset = $lastUpdate->updateId + 1;
    } else {
        $offset = 0;
    }

    return $offset;
}

function base_path(string $dir = ''): string
{
    return dirname(__DIR__) . DIRECTORY_SEPARATOR . $dir;
}

function dd(mixed $message): void
{
    print_r($message);
    die();
}

function enqueueDownload(string $url, int $chatId=1271000): void
{
    $queueFilePath = base_path('src/Storage/queue.json');
    $queue = file_exists($queueFilePath) ? json_decode(file_get_contents($queueFilePath), true) : [];

    $queue[] = [
        'url' => $url,
        'chat_id' => $chatId
    ];

    file_put_contents($queueFilePath, json_encode($queue, JSON_PRETTY_PRINT));
}

function isSoundcloud(string $url): bool
{
    return preg_match('/^https?:\/\/(www\.)?soundcloud\.com\/[a-zA-Z0-9_-]+(\/[a-zA-Z0-9_-]+)?(\/)?(\?.*)?$/', $url) === 1;
}

/**
 * To use this function you need ffmpeg installed on machine
 * I moved yt-dlp.exe here so anyone can use it without downloading
 * but ffmpeg is a must
 * @return string filename of the downloaded file
 */
function downloadSoundcloud(string $soundcloudUrl): string
{
    $escapedUrl = escapeshellarg($soundcloudUrl);
    $outputFolder = base_path("downloads");
    if (!is_dir($outputFolder)) {
        mkdir($outputFolder, 0777, true);
    }

    $randomPart = uniqid() . time();
    $fullFilePath = $outputFolder . DIRECTORY_SEPARATOR . $randomPart . '.mp3';

    $cmd = "yt-dlp.exe -x --audio-format mp3 $escapedUrl -o $fullFilePath --ffmpeg-location C:\Users\Agshin.Nadirov\Downloads\\ffmpeg-2025-07-23-git-829680f96a-full_build\bin\\ffmpeg.exe";
    exec($cmd);
    return $fullFilePath;
}


function downloadFromQueue()
{
    $queueFilePath = base_path('src/Storage/queue.json');

}
