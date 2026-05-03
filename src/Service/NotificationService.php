<?php

namespace App\Service;

use Kreait\Firebase\Contract\Messaging; 
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging\WebPushConfig;

class NotificationService
{
    public function __construct(
        private Messaging $messaging
    ) {}

    public function sendWebPush(string $deviceToken)
    {
        // 1. Создаем базовое уведомление
        $notification = Notification::create('Заголовок пуша', 'Текст уведомления');

        // 2. Настраиваем специфику для Web (иконка, ссылка при клике)
        $config = WebPushConfig::fromArray([
            'notification' => [
                'icon' => 'https://example.com',
            ],
            'fcm_options' => [
                'link' => 'https://example.com',
            ],
        ]);

        // 3. Собираем сообщение
        $message = CloudMessage::fromArray([
            'token' => $deviceToken,
            'notification' => [
                'title' => 'Заголовок пуша',
                'body' => 'Текст уведомления',
            ],
            'webpush' => [
                'notification' => [
                    'icon' => 'https://example.com',
                ],
                'fcm_options' => [
                    'link' => 'https://example.com',
                ],
            ],
        ]);

        try {
            $this->messaging->send($message);
            return "Успешно отправлено";
        } catch (\Exception $e) {
            return "Ошибка: " . $e->getMessage();
        }
    }
}