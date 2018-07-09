<?php

namespace HousingBundle\Service;

use Doctrine\ORM\EntityManager;
use HousingBundle\Entity\Housing;
use HousingBundle\Enum\HousingStateEnum;
use JMS\Serializer\Serializer;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;
use UserBundle\Entity\User;

/**
 * Housing Manager Service
 */
class HousingManager
{
    /**
     *
     * @var Serializer $serializer
     */
    private $serializer;
    /**
     *
     * @var EntityManager $em
     */
    private $em;
    /**
     *
     * @var Security
     */
    private $security;

    /**
     * HousingManager constructor.
     *
     * @param Serializer    $serializer Tools for serialize object
     * @param EntityManager $em         Entity manager argument
     * @param Security      $security   Security context
     */
    public function __construct(Serializer $serializer, EntityManager $em, Security $security)
    {
        $this->serializer = $serializer;
        $this->em = $em;
        $this->security = $security;
    }

    /**
     * Made some check and return the housing for action
     *
     * @param int $id Get the id housing
     *
     * @return Housing
     *
     * @throws   \ErrorException
     * @internal param User $user Get the connected User
     */
    public function getHousingEntity(int $id)
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $this->em->getFilters()->disable('deleted');
        }
        $housing = $this->em->getRepository(Housing::class)->findOneBy(['id' => $id]);

        if ($housing === null) {
            throw new \ErrorException(sprintf('This housing %d does not exist', $id));
        }

        if (!$this->security->isGranted('ROLE_PROPRIETARY') && $this->security->getUser() !== $housing->getUser()) {
            throw new AccessDeniedException('You are not allowed to see this housing');
        }
        return $housing;
    }

    /**
     * Made some check and return All Housings for action
     *
     * @return Housing[]
     *
     */
    public function getAllHousingEntity()
    {
        $filter = [];
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $this->em->getFilters()->disable('deleted');
            return $this->em->getRepository(Housing::class)->findAll();
        }
        if (!$this->security->isGranted('ROLE_ADMIN') && $this->security->isGranted('ROLE_PROPRIETARY')) {
            /**
             *
             * @var User $user
             */
            $user = $this->security->getUser();
            foreach ($user->getHousings() as $housing) {
                $filter['housing'][] = $housing->getId();
            }
            return $this->em->getRepository(Housing::class)->findBy($filter);
        }
        
        return $this->em->getRepository(Housing::class)->findBy(['visible' => true]);
    }

    /**
     *
     * @param Housing $housing Housing selected
     *
     * @return void
     *
     * @throws \ErrorException
     */
    public function isValidHousing(Housing $housing) :void
    {
    }

}
