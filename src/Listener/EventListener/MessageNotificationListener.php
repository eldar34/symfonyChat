<?php

namespace App\Listener\EventListener;

use App\Event\MessageSentEvent;
use App\Notification\ChatMessageNotification;
use App\Repository\MessageRepository;
use App\Repository\UserChatRepository;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Notifier\ChatterInterface;
use Symfony\Component\Notifier\Bridge\Mercure\MercureOptions;
use Symfony\Component\Notifier\Message\ChatMessage;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Twig\Environment;

#[AsEventListener(event: MessageSentEvent::class)]
class MessageNotificationListener
{
    public function __construct(
        private HubInterface $hub,
        private MessageRepository $messageRepository,
        private UserChatRepository $userChatRepository,
        private Environment $twig,
        private ChatterInterface $chatter
        ) {}

    public function __invoke(MessageSentEvent $event)
    {
        $message = $event->getMessage();
        $chat = $message->getChat();
        $senderId = $message->getSenderId();
        
        foreach ($chat->getUserChats() as $userChat) {
            $recipient = $userChat->getUser();
            if ($recipient->getId() === $senderId) {
                // Обновление чата для отправителя
                $this->hub->publish(new Update(
                    "user_notifications_{$recipient->getId()}", 
                    $this->twig->render('_chat/_message_stream.html.twig', [
                        'message' => $message,
                        'chat_id' => $message->getChat()->getId()
                    ]),
                    true
                ));

                continue;
            }

            $userChat = $this->userChatRepository->findOneBy([
                'chat' => $chat,
                'user' => $recipient
            ]);

            // Считаем актуальное кол-во непрочитанных для этого юзера
            $cunreadCount = $this->messageRepository->countUnreadMessages(
                $chat, 
                $userChat->getLastReadAt() ?? new \DateTimeImmutable('@0'),
                $recipient
            );

            // Обновление "счётчик чата" для получателей
            $this->hub->publish(new Update(
                "user_notifications_{$recipient->getId()}", 
                $this->twig->render('_chat/_unread_badge.html.twig', [
                    'chatId' => $chat->getId(),
                    'count' => $cunreadCount
                ]),
                true
            ));
            
            // Обновление чата для получателей
            $this->hub->publish(new Update(
                "user_notifications_{$recipient->getId()}", 
                $this->twig->render('_chat/_message_stream.html.twig', [
                    'message' => $message,
                    'chat_id' => $message->getChat()->getId()
                ]),
                true
            ));

            // WebpPush
            // $notification = new ChatMessageNotification();
            // $this->chatter->send(
            //     $notification->getMercureMessage()->transport('firebase')
            // );

        }
    }
}
