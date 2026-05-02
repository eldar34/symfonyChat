<?php

namespace App\Notification;

use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Message\ChatMessage;
use Symfony\Component\Notifier\Bridge\Firebase\Notification\WebNotification;

class ChatMessageNotification extends Notification
{
    public function __construct(
        // private string $content, // Сюда передадим уже отрендеренный HTML
        // private int $recipientId
    ) {
        parent::__construct('New Message');
    }

    // Создаем метод, который просто возвращает объект сообщения
    public function getMercureMessage(): ChatMessage
    {       
        $content = "Заголовок уведомления";
        // Настройка опций конкретно для Web Push
        $options = (new WebNotification())
            ->icon('https://example.com')
            ->body('Текст вашего пуш-уведомления')
            ->clickAction('https://example.com');

        return new ChatMessage($content, $options);
    }
}