<?php

namespace HousingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Sonata\MediaBundle\Entity\Media;
use ToolsBundle\DataTrait\DateTrait;

/**
 * HousingDocument
 *
 * @ORM\Table(name="housing_document")
 * @ORM\Entity(repositoryClass="HousingBundle\Repository\HousingDocumentRepository")
 */
class HousingDocument
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
     * @ORM\ManyToOne(targetEntity="HousingBundle\Entity\Housing", inversedBy="documents")
     * @ORM\JoinColumn(name="housing_id", referencedColumnName="id")
     */
    private $housing;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=45)
     */
    private $name;

    /**
     * @var Media
     * @ORM\OneToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", cascade={"persist"}, fetch="EAGER")
     */
    private $file;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Housing
     */
    public function getHousing(): ?Housing
    {
        return $this->housing;
    }

    /**
     * @param Housing $housing Set Housing
     *
     * @return HousingDocument
     */
    public function setHousing(Housing $housing): HousingDocument
    {
        $this->housing = $housing;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name Set a name
     *
     * @return HousingDocument
     */
    public function setName(string $name): HousingDocument
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Media
     */
    public function getFile(): ?Media
    {
        return $this->file;
    }

    /**
     * @param Media $file Set a media
     *
     * @return HousingDocument
     */
    public function setFile(Media $file): HousingDocument
    {
        $this->file = $file;

        return $this;
    }
}
