<?php

namespace App\Security\Generator;

/**
 * RandomGenerator class.
 */
class RandomGenerator
{
    /**
     * Original credit to Laravel's Str::random() method.
     *
     * String length is 20 characters
     */
    public function getRandomAlphaNumStr(): string
    {
        $string = '';

        while (($len = \strlen($string)) < 20) {
            $size = 20 - $len;

            $bytes = \random_bytes($size);

            $string .= \substr(
                \str_replace(['/', '+', '='], '', \base64_encode($bytes)),
                0,
                $size
            );
        }

        return $string;
    }
}
