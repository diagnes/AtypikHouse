<?php

namespace HousingBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use ToolsBundle\DataTrait\DateTrait;

/**
 * HousingDetail
 *
 * @ORM\Table(name="housing_detail")
 * @ORM\Entity(repositoryClass="HousingBundle\Repository\HousingDetailRepository")
 */
class HousingDetail
{
    use DateTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=45)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="text")
     */
    private $label;

    /**
     * @ORM\OneToMany(targetEntity="HousingBundle\Entity\HousingDetailValue", mappedBy="detail")
     */
    private $values;

    /**
     * Many Features have One Product.
     * @ORM\ManyToOne(targetEntity="HousingBundle\Entity\HousingType", inversedBy="details", cascade={"persist"})
     * @ORM\JoinColumn(name="housing_type_id", referencedColumnName="id")
     */
    private $housingType;

    /**
     * HousingDetail constructor.
     */
    public function __construct()
    {
        $this->values = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name Set a name
     *
     * @return HousingDetail
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label Set a label
     *
     * @return HousingDetail
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return ArrayCollection|HousingDetailValue[]
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @param ArrayCollection|HousingDetailValue[] $values Set values
     *
     * @return HousingDetail
     */
    public function setValues($values)
    {
        $this->values = $values;

        return $this;
    }

    /**
     * @return null|HousingType
     */
    public function getHousingType()
    {
        return $this->housingType;
    }

    /**
     * @param HousingType $housingType Set housing type
     *
     * @return HousingDetail
     */
    public function setHousingType(?HousingType $housingType)
    {
        $this->housingType = $housingType;

        return $this;
    }
}
