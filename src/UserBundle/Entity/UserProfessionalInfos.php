<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserProfessionalInfos
 *
 * @ORM\Table(name="user_professional_infos")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserProfessionalInfosRepository")
 */
class UserProfessionalInfos
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="UserBundle\Entity\User", inversedBy="professionalInfos")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var string|null
     *
     * @ORM\Column(name="siret", type="string", length=14, nullable=true)
     */
    private $siret;

    /**
     * @var string|null
     *
     * @ORM\Column(name="entreprise", type="string", length=45, nullable=true)
     */
    private $entreprise;

    /**
     * @var string|null
     *
     * @ORM\Column(name="work_number", type="string", length=10, nullable=true)
     */
    private $workNumber;

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
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return null|string
     */
    public function getSiret(): ?string
    {
        return $this->siret;
    }

    /**
     * @param string $siret
     *
     * @return void
     */
    public function setSiret(string $siret): void
    {
        $this->siret = $siret;
    }

    /**
     * @return null|string
     */
    public function getEntreprise(): ?string
    {
        return $this->entreprise;
    }

    /**
     * @param null|string $entreprise
     *
     * @return void
     */
    public function setEntreprise($entreprise): void
    {
        $this->entreprise = $entreprise;
    }

    /**
     * @return null|string
     */
    public function getWorkNumber(): ?string
    {
        return $this->workNumber;
    }

    /**
     * @param null|string $workNumber
     *
     * @return void
     */
    public function setWorkNumber($workNumber): void
    {
        $this->workNumber = $workNumber;
    }
}
