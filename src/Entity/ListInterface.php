<?php

namespace App\Entity;

/**
 * Interface List.
 */
interface ListInterface
{
    /**
     * Get list items.
     */
    public function all(): array;
}
