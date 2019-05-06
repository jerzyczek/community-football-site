<?php
namespace App\Repository;

use App\Entity\Comment;
use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use PhpParser\Node\Expr;
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
//            ->innerJoin(Comment::class, 'cc')
//            ->where('p.id = cc.post_id')
//            ->where($qb->expr()->isNull('cc.parent_comment_id'))
//            ->where('cc.parentComment is null')
            ->where('gu.id = :id OR g.user = :id')
            ->orderBy('p.createdAt', 'DESC');

        $qb->setParameter('id', $user->getId());
        $query = $qb->getQuery()->getResult();

        return $query;
    }

    //get newest posts from every member group
    public function getNewestPosts(User $user)
    {
        $expr = $this->getEntityManager()->getExpressionBuilder();
        $qb = $this->createQueryBuilder('p');
        $em = $this->getEntityManager();
        $qb->select('p')
            ->innerJoin('p.group', 'g', 'p.group=g.id')
            ->innerJoin('g.members', 'gu', 'g.id = gu.group_id')
            ->where($expr->in(
                'p.createdAt',
                $em->createQueryBuilder()
                    ->select($expr->max('p2.createdAt'))
                    ->from(Post::class, 'p2')
                    ->groupBy('p2.group')
                    ->getDQL()
            ))
        ->andWhere('gu.id = :id');

        $qb->setParameter('id', $user->getId());
        $query = $qb->getQuery();

        return $query->getResult();
    }
}