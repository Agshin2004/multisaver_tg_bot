<?php

namespace Services;

use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;

class TelegramService
{
    public function __construct(private Api $telegram)
    {
    }

    public function getUpdates(int $offset, ?int $limit = 1): array
    {
        return $this->telegram->getUpdates([
            'offset' => $offset,
            'limit' => $limit,
            'timeout' => 30,
        ]);
    }

    public function sendText(int $chatId, string $text): void
    {
        try {
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => $text,
            ]);
        } catch (TelegramSDKException $e) {
            echo $e->getMessage();
        }
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
