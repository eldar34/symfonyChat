<?php

namespace App\Repository;

use App\Entity\Chat;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Chat>
 */
class ChatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private EntityManagerInterface $em)
    {
        parent::__construct($registry, Chat::class);
    }

    public function findPrivateChatBetweenUsers(User $userA, User $userB): ?Chat
    {
        return $this->createQueryBuilder('c')
            ->select('c')
            // Join (UserChat) for userA
            ->join('c.userChats', 'uc1')
            // Join (UserChat) for userB 
            ->join('c.userChats', 'uc2')
            ->where('c.is_group = :isGroup')
            ->andWhere('uc1.user = :userA')
            ->andWhere('uc2.user = :userB')
            ->setParameter('isGroup', false)
            ->setParameter('userA', $userA)
            ->setParameter('userB', $userB)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function store(Chat $chat, $isFlush = true): Chat
    {
        $this->em->persist($chat);
        
        if($isFlush){
            $this->em->flush();
        }

        return $chat;
    }

    //    /**
    //     * @return Chat[] Returns an array of Chat objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Chat
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
