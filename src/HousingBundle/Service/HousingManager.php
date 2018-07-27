<?php

namespace HousingBundle\Service;

use AtypikHouseBundle\Entity\Reservation;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\UnitOfWork;
use HousingBundle\Entity\Housing;
use HousingBundle\Entity\HousingDetail;
use HousingBundle\Entity\HousingDetailValue;
use HousingBundle\Entity\HousingType;
use HousingBundle\Enum\HousingStateEnum;
use JMS\Serializer\Serializer;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;
use UserBundle\Entity\User;

/**
 * Housing Manager Service
 *
 * In this service we made all action on a housing
 *
 * PHP version 7.1
 *
 * @category  Service
 * @author    Diagne Stéphane <diagne.stephane@gmail.com>
 * @copyright 2018
 */
class HousingManager
{
    /**
     * @var bool $typeHasChange
     */
    private $typeHasChange;
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
     * @var Security $security
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
        $this->typeHasChange = false;
    }

    /**
     * Made some check and return the housing for action
     *
     * @param string $slug Get the slug targeted housing
     *
     * @return Housing
     *
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @throws \ErrorException
     */
    public function getHousingEntity(string $slug)
    {
        $this->em->getFilters()->enable('deleted');
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $this->em->getFilters()->disable('deleted');
        }
        $housing = $this->em->getRepository(Housing::class)->findOneBy(['slug' => $slug]);

        if ($housing === null) {
            throw new \ErrorException(sprintf('This housing %d does not exist', $slug));
        }

        if (!$this->security->isGranted('ROLE_PROPRIETARY') && $this->security->getUser() !== $housing->getProprietary()) {
            throw new AccessDeniedException('You are not allowed to see this housing');
        }
        return $housing;
    }

    /**
     * Made some check and return the housing for action
     *
     * @param string $slug Get the slug targeted housing
     *
     * @return Housing
     *
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @throws \ErrorException
     */
    public function getHousingFrontEntity(string $slug)
    {
        $housing = $this->em->getRepository(Housing::class)->findOneBy(
            [
            'slug' => $slug,
            'state' => HousingStateEnum::VALIDATED,
            'visible' => true,
            ]
        );

        if ($housing === null) {
            throw new \ErrorException(sprintf('This housing %d does not exist', $slug));
        }

        return $housing;
    }

    /**
     * Made some check and return All Housings for action
     *
     * @return Housing[]
     */
    public function getAllHousingEntity()
    {
        $this->em->getFilters()->enable('deleted');
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $this->em->getFilters()->disable('deleted');
            return $this->em->getRepository(Housing::class)->findAll();
        }
        if (!$this->security->isGranted('ROLE_ADMIN') && $this->security->isGranted('ROLE_PROPRIETARY')) {
            /** @var User $user */
            $user = $this->security->getUser();
            return $user->getHousings();
        }

        return $this->em->getRepository(Housing::class)->findBy(['visible' => true]);
    }

    /**
     * Return All Housings for a proprietary for action
     *
     * @return Housing[]
     */
    public function getAllHousingProprietaryEntity()
    {
        $user = $this->security->getUser();
        $this->em->getFilters()->enable('deleted');

        if ($this->security->isGranted('ROLE_ADMIN')) {
            $this->em->getFilters()->disable('deleted');
        }

        return $this->em->getRepository(Housing::class)->findBy(['proprietary' => $user]);
    }

    /**
     * Made some check and return All Housings for action
     *
     * @param array $housingIds Get the list of housings
     * @param int   $page       Get the page
     *
     * @return Housing[]
     */
    public function getAllHousingFrontEntity(array $housingIds = null, $page = 1)
    {
        $this->em->getFilters()->enable('deleted');
        $filter = [
            'visible' => true,
            'state' => HousingStateEnum::VALIDATED
        ];

        if ($housingIds) {
            $filter['id'] = $housingIds;
        }
        return $this->em->getRepository(Housing::class)->findBy($filter, [], 6, 6 * ($page - 1));
    }


    /**
     * Create a new instance of Housing
     *
     * @return Housing
     */
    public function createNewHousing(): Housing
    {
        $housing = new Housing();
        $housing->setState(HousingStateEnum::CREATED);
        $housing->setVisible(false);
        $housing->setProprietary($this->security->getUser());

        return $housing;
    }

    /**
     *
     * @param  Housing $housing Housing selected
     *
     * @return bool
     */
    public function isValidHousing(Housing $housing) :bool
    {
        return true;
    }

    /**
     * Change the value of housing depending on house type
     *
     * @param Housing         $housing        Housing selected
     * @param HousingDetail[] $housingDetails Get if pass a HousingDetail[] to set in housing
     *
     * @return voiD
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\ORMException
     */
    public function setTypeValueHousing(Housing $housing, $housingDetails = null)
    {
        $housingDetails = $housingDetails ?: $housing->getType()->getDetails();
        $housingValue = $housing->getDetails();

        foreach ($housingDetails as $detail) {
            $noSet = true;

            foreach ($housingValue as $value) {
                if ($detail === $value->getDetail()) {
                    $noSet = false;
                }
            }
            if ($noSet) {
                $value = new HousingDetailValue();
                $value->setDetail($detail);
                $value->setHousing($housing);
                $housing->addDetail($value);
            }
        }
    }

    /**
     * Change the value of housing depending on house type
     *
     * @param Housing         $housing        Housing selected
     * @param HousingDetail[] $housingDetails Get if pass a HousingDetail[] to set in housing
     *
     * @return void
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\ORMException
     */
    public function removeUnusedValueHousing(Housing $housing, $housingDetails = null)
    {
        $housingDetails = $housingDetails ?: $housing->getType()->getDetails();
        $housingValue = $housing->getDetails();

        foreach ($housingValue as $value) {
            $inDetail = false;
            foreach ($housingDetails as $detail) {
                if ($detail->getId() === $value->getDetail()->getId()) {
                    $inDetail = true;
                    break;
                }
            }
            if (!$inDetail) {
                $this->em->remove($value);
            }
        }
    }

    /**
     * Send Notification about Housing
     *
     * @param Housing $housing Get targeted Housing
     *
     * @return void
     */
    public function sendNotification(Housing $housing)
    {
    }

    /**
     * Send Notification about Housing creation
     *
     * @param Housing $housing Get targeted Housing
     *
     * @return void
     */
    public function sendCreationNotification(Housing $housing)
    {
    }

    /**
     * Edition of housing reask for validation
     *
     * @param Housing $housing Get the tergeted Housing
     *
     * @return void
     */
    public function editHousingValidation(Housing $housing)
    {
        if (HousingStateEnum::VALIDATED === $housing->getState()) {
            $housing->setState(HousingStateEnum::VALIDATION_ASK);
        }
    }

    /**
     * Function used by the listener for dispatch change on all housing
     *
     * @param HousingType $housingType Get the housing type targeted
     *
     * @return void
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\ORMException
     */
    public function updateAllHousings(HousingType $housingType)
    {
        foreach ($housingType->getHousings() as $housing) {
            $this->setTypeValueHousing($housing);
            $this->removeUnusedValueHousing($housing);
            $this->em->persist($housing);
        }
        $this->em->flush();
    }
}
