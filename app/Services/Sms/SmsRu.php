<?php

namespace App\Services\Sms;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class SmsRu
 * @package App\Services\Sms
 */
class SmsRu implements SmsSender
{
    private $appId;
    private $url;
    private $client;

    /**
     * SmsRu constructor.
     */
    public function __construct()
    {
        $this->appId = config('smsru.key');
        $this->url = config('smsru.url');
        $this->client = new Client();
    }

    /**
     * @param int $number
     * @param string $text
     * @return void
     */
    public function send(int $number, string $text): void
    {
        try {
            $this->client->post($this->url, [
                'form_params' => [
                    'api_id' => $this->appId,
                    'to'     => '7' . $number,
                    'text'   => $text,
                    'test'   => 1,
                    'json'   => 1,
                ],
            ]);
        } catch (GuzzleException $e) {
            exit($e->getMessage());
        }
    }
}
