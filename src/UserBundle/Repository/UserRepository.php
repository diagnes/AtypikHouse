<?php

namespace UserBundle\Repository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Find all users by role
     *
     * @param string $role Get the targeted role
     *
     * @return array
     */
    public function findByRole($role)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('u')
            ->from($this->_entityName, 'u')
            ->where('u.roles LIKE :roles')
            ->setParameter('roles', '%"'.$role.'"%');

        return $qb->getQuery()->getResult();
    }

    /**
     * Find all users by role
     *
     * @param int $id Get the user id
     *
     * @return int
     */
    public function visbleNotification(int $id)
    {
        $sql = '
            SELECT
              COUNT(un.id) AS Total
            FROM user_notification AS un
              INNER JOIN user AS u ON un.target_user_id = u.id
            WHERE u.id = :id 
            AND state = 1
        ';

        $connection = $this->_em->getConnection();
        $query = $connection->prepare($sql);
        $query->bindParam('id', $id);
        $query->execute();
        $notif = $query->fetchAll();

        return $notif[0]['Total'];
    }
}
