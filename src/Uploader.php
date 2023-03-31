<?php

namespace Fliq\NftStorage;

use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Response;

class Uploader
{
    public function __construct(protected NftStorage $storage, protected array $options = [])
    {
    }

    public function handle(...$resources): PromiseInterface
    {
        $promise = $this->storage->client->requestAsync('POST', 'upload', [
            'multipart' => $this->build($resources),
        ]);

        return $promise->then(function (Response $response) {
            return json_decode($response->getBody()->getContents(), JSON_OBJECT_AS_ARRAY);
        });
    }

    protected function build($resources): array
    {
        $headers = [];
        $parts = [];

        if (is_array($resources)) {
            foreach ($resources as $key => $resource) {
                // recursively resolve part if we have a numeric key.
                if (is_numeric($key)) {
                    $parts = array_merge($parts, $this->build($resource));
                } else { // otherwise we have a string as a key such as 'file.json' => [],
                    $headers = $this->makeHeaders($key);

                    $parts[] = $this->makePart($headers, $resource);
                }
            }
        } else {
            $parts[] = $this->makePart($headers, $resources);
        }

        return $parts;
    }

    protected function makePart(array $headers, $resource): array
    {
        if (is_array($resource)) {
            $resource = json_encode($resource);
        }

        return [
            'name' => 'file',
            'headers' => $headers,
            'contents' => $resource,
        ];
    }

    protected function makeHeaders(string $key): array
    {
        return [
            'Content-Disposition' => 'form-data; name="file"; filename="'.$key.'"',
            'Content-Type' => 'application/octet-stream',
        ];
    }
}
