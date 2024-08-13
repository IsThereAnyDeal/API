<?php
namespace IsThereAnyDeal\SDK\OAuth\Data;

use DateTimeImmutable;

class TokenResponse
{
    public string $error;
    public string $errorDescription;
    public string $hint;
    public string $message;

    public string $type;
    public DateTimeImmutable $expiresIn;
    public string $accessToken;
    public string $refreshToken;
}