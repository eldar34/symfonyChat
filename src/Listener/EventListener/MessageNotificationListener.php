<?php

namespace App\Listener\EventListener;

use App\Event\MessageSentEvent;
use App\Repository\MessageRepository;
use App\Repository\UserChatRepository;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Twig\Environment;

#[AsEventListener(event: MessageSentEvent::class)]
class MessageNotificationListener
{
    public function __construct(
        private HubInterface $hub,
        private MessageRepository $messageRepository,
        private UserChatRepository $userChatRepository,
        private Environment $twig) {}

    public function __invoke(MessageSentEvent $event)
    {
        $message = $event->getMessage();
        $chat = $message->getChat();
        $senderId = $message->getSenderId();
        
        foreach ($chat->getUserChats() as $userChat) {
            $recipient = $userChat->getUser();
            if ($recipient->getId() === $senderId) {
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
            

            // Рендерим Turbo Stream и отправляем в персональный топик юзера
            $this->hub->publish(new Update(
                "user_notifications_{$recipient->getId()}",
                $this->twig->render('_chat/_unread_badge.html.twig', [
                    'chatId' => $chat->getId(),
                    'count' => $cunreadCount
                ])
            ));
        }
    }
}
