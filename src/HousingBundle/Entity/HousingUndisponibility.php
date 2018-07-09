<?php

namespace HousingBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use ToolsBundle\DataTrait\DateTrait;
use ToolsBundle\DataTrait\DeletedDateTrait;
use UserBundle\Entity\Address;
use UserBundle\Entity\User;

/**
 * Housing
 *
 * @ORM\Table(name="housing_undisponibility")
 * @ORM\Entity(repositoryClass="HousingBundle\Repository\HousingRepository")
 */
class HousingUndisponibility
{
    use DeletedDateTrait;

    /**
     *
     * @var int
     *
     * @ORM\Column(name="id",               type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *
     * @ORM\ManyToOne(targetEntity="HousingBundle\Entity\Housing", inversedBy="undisponibility")
     * @ORM\JoinColumn(name="housing_id",                          referencedColumnName="id")
     */
    private $housing;

    /**
     *
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="datetime")
     */
    private $startDate;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id Set id for HousingUndisponibility
     *
     * @return void
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getHousing()
    {
        return $this->housing;
    }

    /**
     * @param Housing|null $housing Set housing for HousingUndisponibility
     *
     * @return void
     */
    public function setHousing($housing)
    {
        $this->housing = $housing;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate(): \DateTime
    {
        return $this->startDate;
    }

    /**
     * @param \DateTime $startDate Set startDate for HousingUndisponibility
     *
     * @return void
     */
    public function setStartDate(\DateTime $startDate)
    {
        $this->startDate = $startDate;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return 'housing_undisponibility';
    }
}
