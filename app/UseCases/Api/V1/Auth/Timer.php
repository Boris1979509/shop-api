<?php

namespace App\UseCases\Api\V1\Auth;

use Carbon\Carbon;

/**
 * Class Timer
 * @package App\UseCases\Api\V1\Auth
 */
class Timer
{
    /**
     * Default seconds
     */
    public const DEFAULT = 60;

    /**
     * @param int $seconds
     * @return array
     */
    public function add(int $seconds = self::DEFAULT): array
    {
        $ttl = Carbon::now()->copy()->addSeconds($seconds);
        return compact('ttl', 'seconds');
    }

    /**
     * @param Carbon $ttl
     * @param Carbon $now
     * @return array
     */
    public function diff(Carbon $ttl, Carbon $now): array
    {
        $seconds = $now->diffInSeconds($ttl);
        return compact('ttl', 'seconds');
    }

}
