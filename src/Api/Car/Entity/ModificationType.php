<?php

namespace App\Api\Car\Entity;

/**
 * Class ModificationType.
 */
class ModificationType
{
    /**
     * Engine modification.
     */
    public const ENGINE = 'engine';

    /**
     * Drivetrain modification.
     */
    public const DRIVE_TRAIN = 'drivetrain';

    /**
     * Handling modification.
     */
    public const HANDLING = 'handling';

    /**
     * Exterior modification.
     */
    public const EXTERIOR = 'exterior';

    /**
     * Interior modification.
     */
    public const INTERIOR = 'interior';

    /**
     * Exhaust modification.
     */
    public const EXHAUST = 'exhaust';

    /**
     * Tune modification.
     */
    public const TUNE = 'tune';

    /**
     * Get the list of modifications.
     */
    public function all(): array
    {
        return [
            self::ENGINE,
            self::DRIVE_TRAIN,
            self::HANDLING,
            self::EXTERIOR,
            self::INTERIOR,
            self::EXHAUST,
            self::TUNE,
        ];
    }
}
