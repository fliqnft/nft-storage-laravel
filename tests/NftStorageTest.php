<?php

// NftStorageTest

use Fliq\NftStorage\NftStorage;
use GuzzleHttp\Psr7\Utils;

beforeEach(function () {
    $this->storage = new NftStorage($_ENV['NFT_STORAGE_KEY']);
});

it('lists stored nft files', function () {
    $result = $this->storage->ls();

    expect($result['ok'])->toBeTrue();
});

it('uploads files', function () {
    $resource = Utils::tryFopen(__DIR__.'/uploads/foo.txt', 'r');

    $response = $this->storage->upload($resource);

    expect($response['ok'])
        ->toBeTrue()
        ->and($response['value'])
        ->toHaveKey('cid');
});

it('can upload json', function () {
    $response = $this->storage->upload([
        'meta.json' => [
            'foo' => 'bar',
        ],
    ]);

    expect($response['ok'])
        ->toBeTrue()
        ->and($response['value'])
        ->toHaveKey('cid');
});
