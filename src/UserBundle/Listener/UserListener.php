<?php

namespace UserBundle\Listener;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Doctrine\ORM\UnitOfWork;
use HousingBundle\Entity\Housing;
use HousingBundle\Entity\HousingDetail;
use HousingBundle\Entity\HousingDetailValue;
use HousingBundle\Entity\HousingType;
use HousingBundle\Service\HousingManager;
use HousingBundle\Service\HousingTypeManager;
use UserBundle\Entity\UserProfessionalInfos;

/**
 * User Listener
 *
 * In this Listener we made all in case of some action in database
 *
 * PHP version 7.1
 *
 * @category  Listener
 * @author    Diagne Stéphane <diagne.stephane@gmail.com>
 * @copyright 2018
 */
class UserListener
{
    /**
     * On the flush make actions
     *
     * @param OnFlushEventArgs $eventArgs Get OnFlush args value
     *
     * @return void
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\ORMException
     */
    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        $em = $eventArgs->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if ($entity instanceof UserProfessionalInfos) {
                $entity->getUser()->addRole('ROLE_PROPRIETARY');
                $classMetadata = $em->getClassMetadata(\get_class($entity));
                $em->getUnitOfWork()->computeChangeSet($classMetadata, $entity);
                $em->persist($entity);
            }
        }
    }
}
