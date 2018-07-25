<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\Valid()
     * @Assert\Length(min = 10, max = 10)
     * @Assert\Regex(pattern="/^(0|\+33)[1-9]([-. ]?[0-9]{2}){4}$/", message="Phone number it's number only")
     *
     * @ORM\Column(name="work_number", type="string", length=10, nullable=true)
     */
    private $workNumber;

    /**
     * Get a Id
     *
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set a Id
     *
     * @param int $id Set a new id
     *
     * @return UserProfessionalInfos
     */
    public function setId(int $id): UserProfessionalInfos
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get a User
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set a User
     *
     * @param User $user Set a new user
     *
     * @return UserProfessionalInfos
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get a Siret
     *
     * @return null|string
     */
    public function getSiret()
    {
        return $this->siret;
    }

    /**
     * Set a Siret
     *
     * @param null|string $siret Set a new siret
     *
     * @return UserProfessionalInfos
     */
    public function setSiret($siret)
    {
        $this->siret = $siret;

        return $this;
    }

    /**
     * Get a Entreprise
     *
     * @return null|string
     */
    public function getEntreprise()
    {
        return $this->entreprise;
    }

    /**
     * Set a Entreprise
     *
     * @param null|string $entreprise Set a new entreprise
     *
     * @return UserProfessionalInfos
     */
    public function setEntreprise($entreprise)
    {
        $this->entreprise = $entreprise;

        return $this;
    }

    /**
     * Get a WorkNumber
     *
     * @return null|string
     */
    public function getWorkNumber()
    {
        return $this->workNumber;
    }

    /**
     * Set a WorkNumber
     *
     * @param null|string $workNumber Set a new workNumber
     *
     * @return UserProfessionalInfos
     */
    public function setWorkNumber($workNumber)
    {
        $this->workNumber = $workNumber;

        return $this;
    }
}
