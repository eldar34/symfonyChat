<?php

namespace App\Service;

use App\DTO\UserChat\CreateUserChatDTO;
use App\Entity\Chat;
use App\Entity\User;
use App\Entity\UserChat;
use App\Factory\UserChatFactory;
use App\Repository\UserChatRepository;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Twig\Environment;


final readonly class UserChatService
{
    public function __construct(
        private UserChatRepository $userChatRepository,
        private UserChatFactory $userChatFactory,
        private HubInterface $hub,
        private Environment $twig
    ) {}

    public function createUserChat(Chat $chat, User $owner, User $recipient): UserChat
    {
        $newUserChatDTO = new CreateUserChatDTO(
            userId: $owner->getId(),
            chatId: $chat->getId(),
            role: 'owner',
            lastReadAt: new \DateTimeImmutable(),
            joinedAt: new \DateTimeImmutable(),
        );

        $userChat = $this->userChatFactory->createFromDto($newUserChatDTO);

        $this->userChatRepository->store($userChat, false);

        $newUserChatDTO = new CreateUserChatDTO(
            userId: $recipient->getId(),
            chatId: $chat->getId(),
            role: 'member',
            lastReadAt: new \DateTimeImmutable(),
            joinedAt: new \DateTimeImmutable(),
        );

        $userChat = $this->userChatFactory->createFromDto($newUserChatDTO);

        $this->userChatRepository->store($userChat, true);


        return $userChat;
    }

    public function updateUserChat(Chat $chat, User $currentUser): UserChat
    {
        $userChat = $this->userChatRepository->findOneBy([
            'chat' => $chat,
            'user' => $currentUser
        ]);

        if ($userChat) {
            $userChat->setLastReadAt(new \DateTimeImmutable());
            $this->userChatRepository->store($userChat);
        }

        $this->hub->publish(new Update(
            "user_notifications_{$currentUser->getId()}", 
            $this->twig->render('_chat/_unread_badge.html.twig', [
                'chatId' => $chat->getId(),
                'count' => 0 
            ])
        ));


        return $userChat;
    }
}
