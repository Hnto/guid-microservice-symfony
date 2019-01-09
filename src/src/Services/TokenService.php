<?php

namespace App\Services;

use Carbon\Carbon;

class TokenService
{
    /**
     * Generate a random token string
     *
     * @return string
     */
    public static function generateTokenValue()
    {
        try {
            $bytes = random_bytes(32);
        } catch (\Exception $exception) {
            $bytes = 123456789;
        }

        $token = bin2hex($bytes);

        return $token;
    }

    /**
     * Generate the expire date time
     * for a token
     *
     * @return Carbon
     */
    public static function generateTokenExpire()
    {
        return Carbon::create()
            ->addDays(1);
    }
}