<?php
namespace IsThereAnyDeal\SDK\OAuth\Data;

class Authorization
{
    public function __construct(
        public readonly string $verifier,
        public readonly string $state,
        public readonly string $url
    ) {}
}