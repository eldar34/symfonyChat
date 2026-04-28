<?php

namespace App\Repository;

use App\Entity\Chat;
use App\Entity\Message;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Message>
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private EntityManagerInterface $em)
    {
        parent::__construct($registry, Message::class);
    }

    public function store(Message $message, $isFlush = true): Message
    {
        $this->em->persist($message);

        if ($isFlush) {
            $this->em->flush();
        }

        return $message;
    }

    public function countUnreadMessages(Chat $chat, \DateTimeInterface $lastReadAt, User $currentUser): int
    {
        return $this->createQueryBuilder('m')
            ->select('count(m.id)')
            ->where('m.chat = :chat')
            ->andWhere('m.created_at > :lastReadAt')
            ->andWhere('m.sender != :currentUserId') // Не считаем свои сообщения
            ->setParameter('chat', $chat) // Передаем объект, Doctrine сама возьмет ID
            ->setParameter('lastReadAt', $lastReadAt)
            ->setParameter('currentUserId', $currentUser)
            ->getQuery()
            ->getSingleScalarResult();
    }

    //    /**
    //     * @return Message[] Returns an array of Message objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Message
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
