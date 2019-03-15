<?php

namespace App\Repository;

use App\Entity\CommentReplies;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CommentReplies|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentReplies|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentReplies[]    findAll()
 * @method CommentReplies[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepliesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CommentReplies::class);
    }

    // /**
    //  * @return CommentReplies[] Returns an array of CommentReplies objects
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
    public function findOneBySomeField($value): ?CommentReplies
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
