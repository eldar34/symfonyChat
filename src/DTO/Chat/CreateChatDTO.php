<?php

namespace App\DTO\Chat;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateChatDTO
{
    public function __construct(
        
        #[Assert\NotBlank(message: 'Название чата не может быть пустым.')]
        #[Assert\Length(max: 255)]
        public string $title,
        
        #[Assert\NotNull]
        #[Assert\Type('bool')]
        public bool $isGroup,
        
        #[Assert\NotBlank]
        #[Assert\Type(\DateTimeImmutable::class)]
        #[Assert\LessThanOrEqual('now', message: 'Дата создания не может превышать текущую дату.')]
        public \DateTimeImmutable $createdAt,
    ) {}
}