<?php

namespace AtypikHouseBundle\Repository;

use AtypikHouseBundle\Entity\Blog;
use Doctrine\ORM\EntityRepository;

/**
 * ArticleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BlogRepository extends EntityRepository
{
    /**
     * Get available bog
     *
     * @param null|int $limit Set the max result for the query
     *
     * @return Blog[]
     */
    public function getllAvailableBlog($limit = null)
    {
        $qb = $this->createQueryBuilder('b');

        $qb
            ->where('b.visible = 1')
            ->andWhere('b.createdAt <= :today')
            ->orderBy('b.createdAt', 'DESC')
            ->setParameter('today', new \DateTime());
        if ($limit) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }
}
