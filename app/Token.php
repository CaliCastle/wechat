<?php

namespace App;

use Cache;
use GuzzleHttp\Client;

class Token {

    const KEY = 'wechat_token';

    /**
     * Guzzle Http client.
     *
     * @var Client
     */
    protected $client;

    /**
     * Access token url.
     *
     * @var string
     */
    protected $accessTokenUrl = "https://api.weixin.qq.com/cgi-bin/token";

    /**
     * Token constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get the access token.
     *
     * @return string
     */
    public function get()
    {
        if (Cache::has(self::KEY)) {
            return Cache::get(self::KEY);
        }

        return $this->generate();
    }

    /**
     * Generate a new access token from server.
     *
     * @return string
     */
    public function generate()
    {
        $response = $this->client
            ->post($this->accessTokenUrl, [
                'query' => [
                    'grant_type' => 'client_credential',
                    'appid'      => config('wechat.id'),
                    'secret'     => config('wechat.secret'),
                ]
            ])->getBody()
            ->getContents();
        $json = json_decode($response, true);

        Cache::put(self::KEY, $json['access_token'], 119);

        return $json['access_token'];
    }
}