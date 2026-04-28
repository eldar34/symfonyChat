<?php

namespace App\Service;

use App\DTO\Chat\CreateChatDTO;
use App\Entity\Chat;
use App\Factory\ChatFactory;
use App\Repository\ChatRepository;


final readonly class ChatService
{
    public function __construct(
        private ChatRepository $chatRepository,
        private ChatFactory $chatFactory
    ) {}

    public function createChat(CreateChatDTO $newChatDTO): Chat
    {
        $chat = $this->chatFactory->createFromDto($newChatDTO);

        $this->chatRepository->store($chat);


        return $chat;
    }

}