<?php

namespace App\Factory;

use App\DTO\UserChat\CreateUserChatDTO;
use App\Entity\UserChat;
use App\Repository\UserRepository;
use App\Repository\ChatRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class UserChatFactory
{
    public function __construct(
        private UserRepository $userRepository,
        private ChatRepository $chatRepository
    ) {}

    public function createFromDto(CreateUserChatDTO $dto): UserChat
    {
        $user = $this->userRepository->find($dto->userId);
        $chat = $this->chatRepository->find($dto->chatId);

        if (!$user || !$chat) {
            throw new NotFoundHttpException('User or Chat not found');
        }

        $userChat = new UserChat();
        $userChat->setUser($user);
        $userChat->setChat($chat);
        $userChat->setRole($dto->role);
        $userChat->setLastReadAt($dto->lastReadAt);
        $userChat->setJoinedAt($dto->joinedAt);

        return $userChat;
    }
}