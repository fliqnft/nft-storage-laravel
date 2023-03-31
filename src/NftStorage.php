<?php

namespace Fliq\NftStorage;

use GuzzleHttp\Client;

class NftStorage
{
    public function __construct(private readonly string $apiKey)
    {
        $this->client = new Client([
            'base_uri' => 'https://api.nft.storage',
            'headers' => [
                'Authorization' => "Bearer $this->apiKey",
            ],
        ]);
    }

    public function upload($resource, array $options = [])
    {
        $uploader = new Uploader($this, $options);

        return $uploader->handle($resource)->wait();
    }

    public function ls()
    {
        $response = $this->client->get('/');

        return json_decode($response->getBody()->getContents(), JSON_OBJECT_AS_ARRAY);
    }

    public function read(string $uri)
    {
    }
}
