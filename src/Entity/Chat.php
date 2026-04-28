<?php

namespace App\Entity;

use App\Repository\ChatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChatRepository::class)]
class Chat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column]
    private ?bool $is_group = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\OneToMany(mappedBy: 'chat', targetEntity: UserChat::class, orphanRemoval: true)]
    private Collection $userChats;

    #[ORM\OneToMany(mappedBy: 'chat', targetEntity: Message::class, orphanRemoval: true)]
    #[ORM\OrderBy(['id' => 'ASC'])]
    private Collection $messages;

    public function __construct()
    {
        $this->userChats = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
    }

    public function getUserChats(): Collection
    {
        return $this->userChats;
    }

    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function getLastMessage(): ?Message
    {
        return $this->messages->last() ?: null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function isGroup(): ?bool
    {
        return $this->is_group;
    }

    public function setIsGroup(bool $is_group): static
    {
        $this->is_group = $is_group;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function addUserChat(UserChat $userChat): static
    {
        if (!$this->userChats->contains($userChat)) {
            $this->userChats->add($userChat);
            $userChat->setChat($this);
        }

        return $this;
    }

    public function removeUserChat(UserChat $userChat): static
    {
        if ($this->userChats->removeElement($userChat)) {
            // set the owning side to null (unless already changed)
            if ($userChat->getChat() === $this) {
                $userChat->setChat(null);
            }
        }

        return $this;
    }

    public function addMessage(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setChat($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getChat() === $this) {
                $message->setChat(null);
            }
        }

        return $this;
    }

}
