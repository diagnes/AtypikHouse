<?php

namespace PaymentBundle\Enum;

use ToolsBundle\Enum\AbstractEnum;

class PaymentStateEnum extends AbstractEnum
{
    public const CREATED = 'created';
    public const VALIDATED = 'validated';
    public const REFUSED = 'refused';

    /**
     * @return array
     */
    public static function toAssoc(): array
    {
        return [
            self::CREATED => 'Créer',
            self::VALIDATED => 'Validée',
            self::REFUSED => 'Refusée',
        ];
    }
}