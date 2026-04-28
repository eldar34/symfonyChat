<?php

namespace App\DTO\UserChat;

use App\Entity\Chat;
use App\Entity\User;
use App\Validator\Constr\EntityExists;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateUserChatDTO
{
    public function __construct(

        #[EntityExists(entityClass: User::class)]
        public int $userId,

        #[EntityExists(entityClass: Chat::class)]
        public int $chatId,

        #[Assert\NotBlank(message: 'Поле role не может быть пустым.')]
        #[Assert\Length(max: 255)]
        public string $role,

        #[Assert\Type(\DateTimeImmutable::class)]
        public ?\DateTimeImmutable $lastReadAt,

        #[Assert\NotBlank(message: 'Поле joined_at не может быть пустым.')]
        #[Assert\Type(\DateTimeImmutable::class)]
        public \DateTimeImmutable $joinedAt,
    ) {}
}