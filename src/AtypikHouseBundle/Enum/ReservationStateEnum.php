<?php

namespace AtypikHouseBundle\Enum;

use ToolsBundle\Enum\AbstractEnum;

class ReservationStateEnum extends AbstractEnum
{
    public const CREATED = 'created';
    public const PENDING = 'pending';
    public const VALIDATED = 'validated';
    public const REFUSED = 'refused';
    public const VALIDATED_CLIENT = 'validated-client';
    public const REFUSED_CLIENT = 'refused-client';
    public const CANCELED = 'canceled';
    public const DONE = 'done';

    /**
     *
     * @return array
     */
    public static function toAssoc(): array
    {
        return [
            self::CREATED => 'Create',
            self::PENDING => 'Pending',
            self::VALIDATED => 'Validate',
            self::REFUSED => 'Refused',
            self::VALIDATED_CLIENT => 'Validated by customer',
            self::REFUSED_CLIENT => 'Refused by customer',
            self::CANCELED => 'Canceled',
            self::DONE => 'Done',
        ];
    }

    /**
     * @param string $state Get the state token
     *
     * @return array|string
     */
    public static function getLabels($state): string
    {
        $states = static::toAssoc();

        return $states[$state];
    }
}