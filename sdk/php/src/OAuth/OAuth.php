<?php
namespace IsThereAnyDeal\SDK\OAuth;

use IsThereAnyDeal\SDK\OAuth\Data\Authorization;
use IsThereAnyDeal\SDK\OAuth\Data\TokenResponse;

class OAuth
{
    private const int CodeVerifierLength = 60;
    private const string AuthorizeEndpoint = "https://isthereanydeal.com/oauth/authorize/";
    private const string TokenEndpoint = "https://isthereanydeal.com/oauth/token/";

    public function __construct(
        private readonly Credentials $credentials
    ) {}

    private function generateToken(int $length): string {
        $charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-._~";

        $verifier = "";
        for ($i=0; $i<$length; $i++) {
            $verifier .= $charset[random_int(0, strlen($charset)-1)];
        }
        return $verifier;
    }

    private function base64url(string $value): string {
        return rtrim(strtr(base64_encode($value), "+/", "-_"), "=");
    }

    /**
     * @param list<EScope> $scope
     */
    public function getAuthorizationUrl(string $redirectUri, array $scope): Authorization {
        $verifier = $this->generateToken(self::CodeVerifierLength);
        $challenge = $this->base64url(hash("sha256", $verifier, true));

        $state = $this->generateToken(20);

        return new Authorization(
            $verifier,
            $state,
            self::AuthorizeEndpoint."?".http_build_query([
                    "response_type" => "code",
                    "client_id" => $this->credentials->clientId,
                    "redirect_uri" => $redirectUri,
                    "scope" => implode(" ", array_map(fn(EScope $scope) => $scope->value, $scope)),
                    "state" => $state,
                    "code_challenge" => $challenge,
                    "code_challenge_method" => "S256"
                ]
            )
        );
    }

    public function getToken(string $redirectUri, string $code, string $verifier): TokenResponse {

        $curl = curl_init(self::TokenEndpoint);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, [
            "grant_type" => "authorization_code",
            "client_id" => $this->credentials->clientId,
            "client_secret" => $this->credentials->clientSecret,
            "redirect_uri" => $redirectUri,
            "code" => $code,
            "code_verifier" => $verifier
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        if ($response === false) {
            throw new \ErrorException();
        }

        $json = json_decode($response, true);
        $token = new TokenResponse();
        if (isset($json['error'])) {
            $token->error = $json['error'];
            $token->errorDescription = $json['error_description'];
            $token->hint = $json['hint'];
            $token->message = $json['message'];
        } else {
            $token->type = $json['token_type'];
            $token->expiresIn = (new \DateTimeImmutable())->setTimestamp(time() + $json['expires_in']);
            $token->accessToken = $json['access_token'];
            $token->refreshToken = $json['refresh_token'];
        }

        return $token;
    }
}