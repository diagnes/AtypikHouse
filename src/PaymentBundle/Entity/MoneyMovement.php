<?php

namespace PaymentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ToolsBundle\DataTrait\DateTrait;

/**
 * MoneyMovement
 *
 * @ORM\Table(name="money_movement")
 * @ORM\Entity(repositoryClass="PaymentBundle\Repository\MoneyMovementRepository")
 */
class MoneyMovement
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
     * @ORM\Column(name="state", type="string", length=45)
     */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(name="action", type="string", length=45)
     */
    private $action;

    /**
     * @var MoneyMovement
     *
     * @ORM\ManyToOne(targetEntity="PaymentBundle\Entity\PaymentInfos", inversedBy="moneyMovements")
     * @ORM\JoinColumn(name="payment_infos_id", referencedColumnName="id")
     */
    private $paymentInfos;

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
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $state Set state
     *
     * @return void
     */
    public function setState(string $state): void
    {
        $this->state = $state;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $action Set action
     *
     * @return void
     */
    public function setAction(string $action)
    {
        $this->action = $action;
    }

    /**
     * @return mixed
     */
    public function getPaymentInfos()
    {
        return $this->paymentInfos;
    }

    /**
     * @param PaymentInfos $paymentInfos Set paymentInfos
     *
     * @return void
     */
    public function setPaymentInfos(PaymentInfos $paymentInfos): void
    {
        $this->paymentInfos = $paymentInfos;
    }
}
