<?php

namespace App\Twig\Components;

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

     public function __construct(
        private UserRepository $userRepository,
        private Security $security,
        private ChatRepository $chatRepository,
        private UserChatRepository $userChatRepository
    ) {}

    public function getConversations(): array
    {
        // Просто вызываем метод репозитория
        return $this->userChatRepository->findMyChatsWithUnreadCount(
            $this->security->getUser(),
            $this->search
        );
    }

}
