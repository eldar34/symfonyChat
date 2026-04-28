<?php

namespace App\Twig\Components;

use App\Entity\Chat;
use App\Entity\User;
use App\Entity\UserChat;
use App\Repository\ChatRepository;
use App\Repository\UserChatRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\LiveResponder;




#[AsLiveComponent]
final class ChatFilterComponent extends AbstractController
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public string $search = '';

    #[LiveProp]
    public ?int $activeChatId = null;

    #[LiveProp(writable: true)]
    public string $newMessage = '';

    #[LiveProp]
    public string $errorMessage = '';

    #[LiveProp]
    public ?UserChat $oponentChat = null;

    #[LiveProp]
    public ?int $selectedConversationId = null;

    #[LiveProp]
    public ?User $selectedUser = null;

     public function __construct(
        private UserRepository $userRepository,
        private Security $security,
        private ChatRepository $chatRepository,
        private UserChatRepository $userChatRepository
    ) {}

    public function getConversations(): array
    {
        // Получаем текущего пользователя, чтобы не показывать его в списке чатов
        $currentUser = $this->security->getUser();

        if ($this->search) {            
            return $this->userRepository->findUsersBySearch($this->search, $currentUser);;
        }
        return $this->userRepository->findAllExceptMe($currentUser);
    }

    public function getNewConversations(): array
    {
        // Просто вызываем метод репозитория
        return $this->userChatRepository->findMyChatsWithUnreadCount(
            $this->security->getUser(),
            $this->search
        );
    }

    public function getChat(): ?Chat
    {
        // Ищем чат между текущим пользователем и получателем
        return $this->chatRepository->findPrivateChatBetweenUsers(
            $this->security->getUser(),
            $this->selectedUser
        );
    }
}
