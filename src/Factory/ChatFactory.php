<?php

namespace App\Factory;

use App\Entity\Chat;
use App\DTO\Chat\CreateChatDTO;

final class ChatFactory
{
    public function createFromDto(CreateChatDTO $dto): Chat
    {
        $chat = new Chat();
        
        $chat->setTitle($dto->title);
        $chat->setIsGroup($dto->isGroup);
        $chat->setCreatedAt($dto->createdAt);

        return $chat;
    }
}