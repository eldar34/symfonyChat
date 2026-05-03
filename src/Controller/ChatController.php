<?php

namespace App\Controller;

use App\DTO\Chat\CreateChatDTO;
use App\DTO\Message\CreateMessageDTO;
use App\Entity\Chat;
use App\Entity\User;
use App\Event\MessageSentEvent;
use App\Form\MessageType;
use App\Listener\EventListener\MessageNotificationListener;
use App\Repository\ChatRepository;
use App\Service\ChatService;
use App\Service\MessageService;
use App\Service\NotificationService;
use App\Service\UserChatService;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Attribute\Route;

final class ChatController extends AbstractController
{
    #[Route('/chat', name: 'chat_index')]
    public function index(): Response
    {
        return $this->render('chat/index.html.twig', [
            'controller_name' => 'ChatController',
        ]);
    }


    #[Route('/chat/{user_id?}/{chat_id?}', name: 'chat_show')]
    public function show(
        ChatRepository $chatRepository,
        Security $security,
        ChatService $chatService,
        UserChatService $userChatService,
        #[MapEntity(mapping: ['chat_id' => 'id'])] ?Chat $chat = null,
        #[MapEntity(mapping: ['user_id' => 'id'])] ?User $user = null
    ): Response {
        if (!$chat && !$user) {
            throw $this->createNotFoundException('Не указан ни чат, ни пользователь');
        }

        $currentUser = $security->getUser();
        $recipient = $user;

        $chat = $chatRepository->findPrivateChatBetweenUsers($currentUser, $user);
        
        // 1. Если чата нет — создаем "лениво"
        if (!$chat) {
            $newChatDTO = new CreateChatDTO(
                title: 'Private', 
                isGroup: '',             
                createdAt: new \DateTimeImmutable()                         
            );

            $chat = $chatService->createChat($newChatDTO);

            $userChat = $userChatService->createUserChat($chat, $currentUser, $recipient);
        }

        // Обновление UserChat
        $userChat = $userChatService->updateUserChat($chat, $currentUser);

        $messageDTO = new CreateMessageDTO(
            sender_id: $currentUser->getId(), 
            chat_id: $chat->getId(),             
            type: 'text'                         
        );

        $form = $this->createForm(MessageType::class, $messageDTO, [
            'action' => $this->generateUrl('chat_send', ['id' => $chat->getId()]),
            'method' => 'POST',
        ]);


        return $this->render('chat/show.html.twig', [
            'chat' => $chat,
            'form' => $form->createView(),
            'recipient' => $recipient
        ]);
    }

    #[Route('/chat-send/{id}', name: 'chat_send', methods: ['POST'])]
    public function send(
        Request $request, 
        Security $security, 
        MessageService $messageService,
        NotificationService $notificationService,
        EventDispatcherInterface $eventDispatcher,
        HubInterface $hub,
        #[MapEntity(mapping: ['id' => 'id'])] ?Chat $chat = null
    )
    {
                
        // 1. Получаем участников
        $currentUser = $security->getUser();
        
        $messageDTO = new CreateMessageDTO(
            sender_id: $currentUser->getId(), 
            chat_id: $chat->getId(),             
            type: 'text'                         
        );

        // 2. Заполняем форму
        $form = $this->createForm(MessageType::class, $messageDTO, [
            'action' => $this->generateUrl('chat_send', ['id' => $chat->getId()]),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);   
       
        $notificationService->sendWebPush('test');    

        // 3. Валидация
        if ($form->isSubmitted() && $form->isValid()) {
        
        
            // Создаем Entity из валидного DTO
            $message = $messageService->createMessage($messageDTO);

            $eventDispatcher->dispatch(new MessageSentEvent($message));

            

            return new Response('', Response::HTTP_NO_CONTENT);
        }

        // 4. ОБРАБОТКА ОШИБОК (если валидация не прошла)
        // Turbo обновляет только содержимое формы внутри turbo-frame
        return $this->render('_chat/_form.html.twig', [
            'form' => $form,
            'chat' => $chat
        ], new Response('', Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
