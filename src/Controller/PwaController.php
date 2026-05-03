<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PwaController extends AbstractController
{
    #[Route('/pwa', name: 'pwa_index')]
    public function index(): Response
    {
        return $this->render('pwa/index.html.twig', [
            'controller_name' => 'ChatController',
        ]);
    }

    #[Route('/api/pwa/store-token', methods: ['POST'])]
    public function storeToken(Request $request, EntityManagerInterface $em): Response
    {
        $data = $request->toArray();
        $token = $data['token'] ?? null;
        $user = $this->getUser();

        if (!$token || !$user) {
            return $this->json(['error' => 'Invalid data'], 400);
        }

        // Логика сохранения: найдите сущность User и обновите поле fcmToken
        // $user->setFcmToken($token);
        // $em::flush();

        return $this->json(['status' => 'ok']);
    }
}
