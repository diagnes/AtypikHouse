<?php

namespace HousingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Sonata\MediaBundle\Entity\Media;
use ToolsBundle\DataTrait\DateTrait;

/**
 * HousingImages
 *
 * @ORM\Table(name="housing_images")
 * @ORM\Entity(repositoryClass="HousingBundle\Repository\HousingImagesRepository")
 */
class HousingImages
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
     * @var Housing
     *
     * @ORM\ManyToOne(targetEntity="HousingBundle\Entity\Housing", inversedBy="images")
     * @ORM\JoinColumn(name="housing_id", referencedColumnName="id")
     */
    private $housing;

    /**
     * @var Media
     *
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
    public function getHousing(): Housing
    {
        return $this->housing;
    }

    /**
     * @param mixed $housing
     *
     * @return void
     */
    public function setHousing(Housing $housing)
    {
        $this->housing = $housing;
    }

    /**
     * @return Media
     */
    public function getFile(): Media
    {
        return $this->file;
    }

    /**
     * @param Media $file
     *
     * @return void
     */
    public function setFile(Media $file): void
    {
        $this->file = $file;
    }
}
