<?php

namespace App\Notifications\Telegram;

use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class SimpleMessage extends Notification
{
    protected $chat_id;
    protected $message;

    public function __construct($chat_id, $message)
    {
        $this->chat_id = $chat_id;
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['telegram'];
    }


    public function toTelegram($notifiable)
    {
        return TelegramMessage::create()
            ->content($this->message)
            ->to($this->chat_id);
    }
}
