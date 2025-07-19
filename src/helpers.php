<?php

function updateOffset(&$offset, $updateId)
{
    $offset = $updateId + 1; // last id + 1 so it will not send old messages
}

function downloadSoundcloud()
{
}
