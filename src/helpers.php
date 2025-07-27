<?php

function updateOffset(&$offset, $updateId)
{
    $offset = $updateId + 1; // last id + 1 so it will not send old messages
}

/**
 * To use this function you need ffmpeg installed on machine
 * I moved yt-dlp.exe here so anyone can use it without downloading
 * but ffmpeg is a must
 * @return void
 */
function downloadSoundcloud(string $soundcloudUrl): void
{
    $escapedUrl = escapeshellarg($soundcloudUrl);
    $cmd = "yt-dlp.exe -x --audio-format mp3 $escapedUrl";
    exec($cmd); // shell_exec can be used since we do not grab returned value
}
