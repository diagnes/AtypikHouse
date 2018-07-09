<?php

namespace ToolsBundle\DataTrait;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait DeletedDateTrait
 */
trait DeletedDateTrait
{
    /**
     * @var \DateTime | null
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @return \DateTime|null
     */
    public function getDeletedAt(): ?\DateTime
    {
        return $this->deletedAt;
    }

    /**
     * @param \DateTime|null $deletedAt Set deleted Date
     *
     * @return void
     */
    public function setDeletedAt($deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }
}
