<?php

namespace App\DTO\Message;

use App\Entity\Chat;
use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constr\EntityExists;

final class CreateMessageDTO
{
    public function __construct(

        #[EntityExists(entityClass: User::class)]
        public ?int $sender_id = null,

        #[EntityExists(entityClass: Chat::class)]
        public ?int $chat_id = null,
        
        #[Assert\NotBlank(message: 'Сообщение не может быть пустым')]
        #[Assert\Length(
            min: 1,
            max: 500,
            minMessage: 'Сообщение слишком короткое',
            maxMessage: 'Сообщение не может быть длиннее {{ limit }} символов'
        )]
        public string $content = '',
        
        #[Assert\Choice(choices: ['text', 'image', 'file'], message: 'Выбран некорректный тип')]
        public string $type = 'text',
    ) {}
}