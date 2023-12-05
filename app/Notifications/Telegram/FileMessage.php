<?php

namespace App\Notifications\Telegram;

use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;
use NotificationChannels\Telegram\TelegramFile;

class FileMessage extends Notification
{
    protected $chat_id;
    protected $message;
    protected $file;
    protected $file_name;

    public function __construct($chat_id, $message, $file, $file_name)
    {
        $this->chat_id = $chat_id;
        $this->message = $message;
        $this->file = $file;
        $this->file_name = $file_name;
    }

    public function via($notifiable)
    {
        return ['telegram'];
    }


    public function toTelegram($notifiable)
    {
        return TelegramFile::create()
            ->content($this->message)
            ->document($this->file, $this->file_name)
            ->to($this->chat_id);
    }
}
