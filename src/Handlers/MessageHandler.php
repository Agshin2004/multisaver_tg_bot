<?php

namespace Handlers;

use Services\TelegramService;
use Telegram\Bot\Objects\Message;

class MessageHandler
{


    public function __construct(private TelegramService $tgService)
    {
    }

    public function handle(Message $message): void
    {
        $chatId = $message->getChat()->getId();
        $text = $message->getText();

        if ($text === '/start') {
            $this->tgService->sendText($chatId, 'Welcome to the bot!');
//            $this->tgService->sendSticker($chatId, 'https://cdn2.combot.org/welcomegooferz/webp/0xf09f8ea3.webp');

            return;
        }

//        if (!empty($text) && isSoundcloud($text)) {
            enqueueDownload($text, $chatId);
            return;
//        }

        $this->tgService->sendText($chatId, $text);

//        $this->tgService->sendPhoto($chatId, 'https://c1.wallpaperflare.com/preview/589/683/831/napoleon-bonaparte-france-emperor-reiter.jpg');
    }
}
