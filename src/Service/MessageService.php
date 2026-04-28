<?php

namespace App\Service;

use App\DTO\Message\CreateMessageDTO;
use App\Factory\MessageFactory;
use App\Entity\Message;
use App\Repository\MessageRepository;


final readonly class MessageService
{
    public function __construct(
        private MessageRepository $messageRepository,
        private MessageFactory $messageFactory
    ) {}

    public function createMessage(CreateMessageDTO $messageDTO): Message
    {
        // 1. Создание сущности
        $message = $this->messageFactory->createFromDto($messageDTO);
    
        // 2. Сохранение
        $this->messageRepository->store($message);

        return $message;
    }
}