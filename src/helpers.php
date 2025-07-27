<?php

function updateOffset(&$offset, $updateId)
{
    $offset = $updateId + 1; // last id + 1 so it will not send old messages
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

    $cmd = "yt-dlp.exe -x --audio-format mp3 $escapedUrl -o $fullFilePath";
    exec($cmd);
    return $fullFilePath;
}
