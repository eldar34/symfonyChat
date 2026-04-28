<?php

namespace App\Entity;

use App\Repository\UserChatRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserChatRepository::class)]
class UserChat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'userChats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null; 

    #[ORM\ManyToOne(targetEntity: Chat::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Chat $chat = null;

    #[ORM\Column(length: 255)]
    private ?string $role = 'member'; 

    #[ORM\Column(nullable: true)] // Обязательно nullable, так как при вступлении еще нет прочитанных
    private ?\DateTimeImmutable $last_read_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $joined_at = null;

    public function __construct()
    {
        $this->joined_at = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(User $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getChatId(): ?Chat
    {
        return $this->chat_id;
    }

    public function setChatId(Chat $chat_id): static
    {
        $this->chat_id = $chat_id;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getLastReadAt(): ?\DateTimeImmutable
    {
        return $this->last_read_at;
    }

    public function setLastReadAt(\DateTimeImmutable $last_read_at): static
    {
        $this->last_read_at = $last_read_at;

        return $this;
    }

    public function getJoinedAt(): ?\DateTimeImmutable
    {
        return $this->joined_at;
    }

    public function setJoinedAt(\DateTimeImmutable $joined_at): static
    {
        $this->joined_at = $joined_at;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getChat(): ?Chat
    {
        return $this->chat;
    }

    public function setChat(?Chat $chat): static
    {
        $this->chat = $chat;

        return $this;
    }
}
