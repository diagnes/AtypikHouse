<?php

namespace HousingBundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\UnitOfWork;
use HousingBundle\Entity\HousingDetail;
use HousingBundle\Entity\HousingType;
use JMS\Serializer\Serializer;
use Symfony\Component\Security\Core\Security;
use UserBundle\Entity\User;

/**
 * Housing Manager Service
 */
class HousingTypeManager
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
     * @var HousingManager
     */
    private $housingManager;

    /**
     * HousingManager constructor.
     *
     * @param Serializer     $serializer     Tools for serialize object
     * @param EntityManager  $em             Entity manager argument
     * @param Security       $security       Security context
     * @param HousingManager $housingManager Get housing manager
     */
    public function __construct(Serializer $serializer, EntityManager $em, Security $security, HousingManager $housingManager)
    {
        $this->serializer = $serializer;
        $this->em = $em;
        $this->security = $security;
        $this->housingManager = $housingManager;
    }

    /**
     * Made some check and return the housing for action
     *
     * @param string $slug Get the id housing
     *
     * @return HousingType
     *
     * @throws   \ErrorException
     * @internal param User $user Get the connected User
     */
    public function getHousingTypeEntity(string $slug)
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $this->em->getFilters()->disable('deleted');
        }
        $housingType = $this->em->getRepository(HousingType::class)->findOneBy(['slug' => $slug]);

        if ($housingType === null) {
            throw new \ErrorException(sprintf('This housingType slug:%d does not exist or has been deleted', $slug));
        }

        return $housingType;
    }

    /**
     * Check if it's possible to delete HousingType
     *
     * @param HousingType $housingType The Housing Type targeted
     *
     * @return void
     *
     * @throws \ErrorException
     */
    public function isDeletable(HousingType $housingType)
    {
        if ($housingType->getHousings()->first()) {
            throw new \ErrorException(sprintf('The %s category associated to house', $housingType->getName()));
        }
    }

    /**
     * Made some check and return All Housings type for action
     *
     * @return HousingType[]
     */
    public function getAllHousingTypeEntity()
    {
        $filter = [];
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $this->em->getFilters()->disable('deleted');
            return $this->em->getRepository(HousingType::class)->findAll();
        }

        return $this->em->getRepository(HousingType::class)->findAll($filter);
    }

    /**
     * Function used by the listener for dispatch change on all housing
     *
     * @param HousingType     $housingType    Get the housing type targeted
     * @param HousingDetail[] $housingDetails Get if pass a HousingDetail[] to set in housing
     * @param EntityManager   $em             Get entity manager from listener
     *
     * @return void
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\ORMException
     */
    public function updateAllHousings(HousingType $housingType, $housingDetails, EntityManager $em)
    {
        foreach ($housingType->getHousings() as $housing) {
            $this->housingManager->setTypeValueHousing($housing, $housingDetails);
            $this->housingManager->removeUnusedValueHousing($housing, $housingDetails);
            $classMetadata = $em->getClassMetadata(\get_class($housing));
            $em->getUnitOfWork()->computeChangeSet($classMetadata, $housing);
            $em->persist($housing);
        }
    }
}
