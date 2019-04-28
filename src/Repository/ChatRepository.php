<?php

namespace App\Repository;

use App\Entity\Chat;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Chat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Chat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Chat[]    findAll()
 * @method Chat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChatRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Chat::class);
    }

    public function getHistoryQuery(User $user, int $userIdSecond)
    {
        //maybe some day we will have friends :)
        $query =  $this->createQueryBuilder('c');

        $query->andWhere($query->expr()->andX(
            $query->expr()->eq('c.user1', ':user1'),
            $query->expr()->eq('c.user2', ':user2')
        ));
        $query->orWhere($query->expr()->andX(
            $query->expr()->eq('c.user1', ':user2'),
            $query->expr()->eq('c.user2', ':user1')
        ));

        $query->setParameter('user1', $user->getId());
        $query->setParameter('user2', $userIdSecond);

        return $query->getQuery();
    }
    // /**
    //  * @return Chat[] Returns an array of Chat objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Chat
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
