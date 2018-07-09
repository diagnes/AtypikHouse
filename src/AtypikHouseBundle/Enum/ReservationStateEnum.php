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
            self::CREATED => 'Créer',
            self::PENDING => 'En cours',
            self::VALIDATED => 'Validée',
            self::REFUSED => 'Refusée',
            self::VALIDATED_CLIENT => 'Validée par client',
            self::REFUSED_CLIENT => 'Refusée par le client',
            self::CANCELED => 'Annulé',
            self::DONE => 'Terminé',
        ];
    }
}