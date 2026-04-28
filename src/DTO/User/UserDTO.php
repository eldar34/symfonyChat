<?php

namespace App\DTO\User;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class UserDTO
{
    public function __construct(
        public int $id,
        
        #[Assert\NotBlank]
        #[Assert\Email]
        public string $email,
    ) {}
}