<?php
namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Entity\User;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function getAllPosts(User $user)
    {
        $qb = $this->createQueryBuilder('p');

        $qb->select('p')
            ->innerJoin('p.group','g', 'p.group=g.id')
            ->innerJoin('g.members', 'gu', 'g.id=gu.group_id')
            ->where('gu.id = :id OR g.user = :id')
            ->orderBy('p.createdAt', 'DESC');

        $qb->setParameter('id', $user->getId());
        $query = $qb->getQuery();

        return $query->getResult();
    }

}