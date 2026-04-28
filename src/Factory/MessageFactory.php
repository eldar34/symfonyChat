<?php

namespace App\Factory;

use App\DTO\Message\CreateMessageDTO;
use App\Entity\Message;
use App\Repository\UserRepository;
use App\Repository\ChatRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class MessageFactory
{
    public function __construct(
        private UserRepository $userRepository,
        private ChatRepository $chatRepository
    ) {}

    public function createFromDto(CreateMessageDTO $dto): Message
    {
        $sender = $this->userRepository->find($dto->sender_id);
        $chat = $this->chatRepository->find($dto->chat_id);

        if (!$sender || !$chat) {
            throw new NotFoundHttpException('User or Chat not found');
        }

        $message = new Message();
        $message->setSender($sender);
        $message->setChat($chat);
        $message->setContent($dto->content);
        $message->setType($dto->type);
        $message->setCreatedAt(new \DateTimeImmutable());

        return $message;
    }
}