<?php

namespace App\Twig\Components;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\Attribute\LiveArg;


#[AsLiveComponent]
final class ChatComponent
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public string $search = '';

    #[LiveProp(writable: true)]
    public string $newMessage = '';

    #[LiveProp]
    public ?int $selectedConversationId = null;

    #[LiveProp]
    public ?User $currentUser = null;

     public function __construct(
        private UserRepository $userRepository,
        private Security $security
    ) {}

    #[LiveAction]
    public function selectConversation(#[LiveArg] int $id): void
    {
        $this->selectedConversationId = $id;
        $this->currentUser = $this->userRepository->find($id);
        
    }

    #[LiveAction]
    public function sendMessage(): void
    {
        if (empty($this->newMessage)) return;
        
        // Здесь логика сохранения в БД
        
        $this->newMessage = ''; // Очищаем поле после отправки
    }

    public function getConversations(): array
    {
        // Получаем текущего пользователя, чтобы не показывать его в списке чатов
        $currentUser = $this->security->getUser();

        if ($this->search) {
            return $this->userRepository->findUsersBySearch($this->search, $currentUser);
        }

        
        return $this->userRepository->findAllExceptMe($currentUser);
    }
}
