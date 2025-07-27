<?php

namespace Services;

use Telegram\Bot\Api;

class TelegramService
{
    public function __construct(private Api $telegram)
    {
    }

    public function getUpdates(int $offset): array
    {
        return $this->telegram->getUpdates([
            'offset' => $offset,
            'timeout' => 30,
        ]);
    }

    public function sendText(int $chatId, string $text): void
    {
        downloadSoundcloud($text);

        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => $text,
        ]);
    }

    public function sendPhoto(int $chatId, string $url): void
    {
        $this->telegram->sendPhoto([
            'chat_id' => $chatId,
            'photo' => \Telegram\Bot\FileUpload\InputFile::create($url),
        ]);
    }

    public function sendAudio(int $chatId, string $url): void
    {
        $this->telegram->sendAudio([
            'chat_id' => $chatId,
            'audio' => \Telegram\Bot\FileUpload\InputFile::create($url)
        ]);
    }

    public function sendSticker(int $chatId, string $url)
    {
        $this->telegram->sendSticker([
            'chat_id' => $chatId,
            'sticker' => \Telegram\Bot\FileUpload\InputFile::create($url),
        ]);
    }
}
