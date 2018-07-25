<?php

namespace PaymentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ToolsBundle\DataTrait\DateTrait;
use Payum\Core\Model\Token;

/**
 * PaymentToken
 *
 * @ORM\Table
 * @ORM\Entity
 */
class PaymentToken extends Token
{
    use DateTrait;
}
