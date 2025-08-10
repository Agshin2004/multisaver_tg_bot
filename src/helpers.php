<?php

use Services\TelegramService;

function updateOffset(&$offset, $updateId)
{
    $offset = $updateId + 1; // last id + 1 so it will not send old messages
}

function getStoredOffset(string $offsetFile): int
{
    return file_exists($offsetFile) ? (int) file_get_contents($offsetFile) : 0;
}

function base_path(string $dir = ''): string
{
    return dirname(__DIR__).DIRECTORY_SEPARATOR.$dir;
}

function dd(mixed $message): void
{
    print_r($message);
    die();
}

function isSoundcloud(string $url): bool
{
    return preg_match('/^https?:\/\/(www\.)?soundcloud\.com\/[a-zA-Z0-9_-]+(\/[a-zA-Z0-9_-]+)?(\/)?(\?.*)?$/',
            $url) === 1;
}

/**
 * Helper function that enqueues link to queue.json so queue_worker.php
 * can pick it up and process
 * @param  string  $url
 * @param  int  $chatId
 * @return void
 */
function enqueueDownload(string $url, int $chatId = 1271000): void
{
    $queueFilePath = base_path('src/Storage/queue.json');
    $queue = file_exists($queueFilePath) ? json_decode(file_get_contents($queueFilePath), true) : [];

    $queue[] = [
        'url' => $url,
        'chat_id' => $chatId
    ];

    file_put_contents($queueFilePath, json_encode($queue, JSON_PRETTY_PRINT));
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

    $randomPart = uniqid().time();
    $fullFilePath = $outputFolder.DIRECTORY_SEPARATOR.$randomPart.'.mp3';

//    $cmdWork = "yt-dlp.exe -x --audio-format mp3 $escapedUrl -o $fullFilePath --ffmpeg-location C:\Users\Agshin.Nadirov\Downloads\\ffmpeg-2025-07-23-git-829680f96a-full_build\bin\\ffmpeg.exe";

    // Execute yt-dlp to extract best available audio from a YouTube URL, convert to MP3, embed thumbnail & metadata,
    // suppress warnings, retry on failure, and save output to specified path.
    $cmd = "yt-dlp.exe --no-playlist --extract-audio --audio-format mp3 --audio-quality 0 ".
        "--embed-thumbnail --add-metadata --quiet ".
        "--retries 3 --fragment-retries 3 --user-agent 'Mozilla/5.0' ".
        "-o $fullFilePath $escapedUrl";

    exec($cmd);
    return $fullFilePath;
}

