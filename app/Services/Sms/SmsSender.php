<?php

namespace App\Services\Sms;
/**
 * Interface SmsSender
 * @package App\Services\Sms
 */
interface SmsSender
{
    /**
     * @param integer $number
     * @param string $text
     */
    public function send(int $number, string $text);

}
