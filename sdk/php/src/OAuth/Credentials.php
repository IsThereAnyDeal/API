<?php
namespace IsThereAnyDeal\SDK\OAuth;

class Credentials
{
    public function __construct(
        public readonly string $clientId,
        public readonly string $clientSecret
    ) {}
}