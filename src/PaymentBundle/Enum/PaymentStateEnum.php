<?php

namespace PaymentBundle\Enum;

use ToolsBundle\Enum\AbstractEnum;

class PaymentStateEnum extends AbstractEnum
{
    public const CREATED = 'created';
    public const COMPLETED = 'completed';
    public const REFUSED = 'refused';

    /**
     * @return array
     */
    public static function toAssoc(): array
    {
        return [
            self::CREATED => 'Create',
            self::COMPLETED => 'Completed',
            self::REFUSED => 'Refused',
        ];
    }
}