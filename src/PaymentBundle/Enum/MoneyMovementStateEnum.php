<?php

namespace PaymentBundle\Enum;

use ToolsBundle\Enum\AbstractEnum;

class MoneyMovementStateEnum extends AbstractEnum
{
    public const REFUND = 'refund';
    public const PAYIN = 'payin';
    public const PAYOUT = 'payout';

    /**
     * @return array
     */
    public static function toAssoc(): array
    {
        return [
            self::REFUND => 'Remboursement',
            self::PAYIN => 'Paiement entrant',
            self::PAYOUT => 'Paiement sortant',
        ];
    }
}