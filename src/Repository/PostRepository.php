<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function findLatest(Tag $tag = null)
    {
        $qb = $this->createQueryBuilder('p');
        $qb->orderBy('p.createdAt', 'desc');

        if ($tag) {
            $qb->leftJoin('p.tags', 't')
                ->andWhere('t = :tag')
                ->setParameter('tag', $tag);
        }

        return $qb->getQuery()->getResult();
    }
}
