<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserChat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserChat>
 */
class UserChatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private EntityManagerInterface $em)
    {
        parent::__construct($registry, UserChat::class);
    }

    public function store(UserChat $userChat, $isFlush = true): UserChat
    {
        $this->em->persist($userChat);
        
        if($isFlush){
            $this->em->flush();
        }

        return $userChat;
    }

    public function findMyChatsWithUnreadCount(User $currentUser, ?string $search = null): array
    {
        // 1. Сначала получаем существующие чаты (они нужны всегда)
        $qb = $this->createQueryBuilder('uc')
            ->innerJoin('uc.chat', 'c')
            ->innerJoin('c.userChats', 'other_uc')
            ->innerJoin('other_uc.user', 'other_user')
            ->leftJoin('c.messages', 'm', 'WITH', 'm.created_at > uc.last_read_at AND m.sender != :currentUserId')
            ->select([
                'c.id as chatId',
                'other_user.id as userId',
                'other_user.email as email',
                'COUNT(m.id) as unreadCount'
            ])
            ->where('uc.user = :currentUser')
            ->andWhere('other_user != :currentUser')
            ->andWhere('c.is_group = false')
            ->setParameter('currentUser', $currentUser)
            ->setParameter('currentUserId', $currentUser->getId());

        if ($search) {
            $searchTerm = mb_strtolower($search, 'UTF-8');

            $qb->andWhere('LOWER(other_user.email) LIKE :search')
                ->setParameter('search', '%' . $searchTerm . '%');
        }

        $existingChats = $qb->groupBy('c.id', 'other_user.id')->getQuery()->getResult();

        // 2. Если поиска НЕТ — возвращаем только чаты
        if (!$search) {
            return $existingChats;
        }

        // 3. Если поиск ЕСТЬ — ищем пользователей БЕЗ чатов
        $existingUserIds = array_column($existingChats, 'userId');
        $existingUserIds[] = $currentUser->getId();

        $usersQb = $this->em->createQueryBuilder()
            ->select([
                'u.id as userId', 
                'u.email as email',
            ])
            ->from(\App\Entity\User::class, 'u')
            ->where('u.id NOT IN (:ids)')
            ->andWhere('u.email LIKE :search')
            ->setParameter('ids', $existingUserIds)
            ->setParameter('search', '%' . $search . '%')
            ->setMaxResults(10);

        $newUsersData = $usersQb->getQuery()->getResult();

        $newUsers = array_map(function($user) {
            return [
                'chatId' => null,
                'userId' => $user['userId'],
                'email' => $user['email'],
                'unreadCount' => 0,
            ];
        }, $newUsersData);

        return array_merge($existingChats, $newUsers);
    }

    //    /**
    //     * @return UserChat[] Returns an array of UserChat objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?UserChat
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
