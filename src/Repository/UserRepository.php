<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function createFriendsQueryBuilder(User $user)
    {
        //maybe some day we will have friends :)
        return $this->createQueryBuilder('p')
            ->andWhere('p.id != :id')
            ->setParameter('id', $user->getId())
            ->orderBy('p.firstname', 'ASC');
    }

    public function getUsersByIdsIndexed(array $ids = [])
    {
        $query =  $this->createQueryBuilder('p', 'p.id');

        return $query->where($query->expr()->in('p.id', $ids))->getQuery()->getResult();
    }
}
