<?php

namespace App\Services;

use GuzzleHttp\Client;

class PaymentService
{

    const BASE_URI = 'https://superpay.view.agentur-loop.com';

    /**
     * @var \GuzzleHttp\Client
     */
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => self::BASE_URI,
        ]);
    }


    public function pay(int $orderId, string $email, float $value): string
    {
        return $this->client->get('/pay', [
            'body' => \json_encode([
                'order_id'       => $orderId,
                'customer_email' => $email,
                'value'          => $value,
            ]),
        ])->getBody()->getContents();
    }
}
