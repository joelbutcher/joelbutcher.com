<?php

namespace App\Http\Integrations\Twitter\Requests;

use App\Http\Integrations\Twitter\TwitterConnector;
use App\Services\Twitter\Support\Consumer;
use App\Services\Twitter\Support\Exceptions\TwitterOAuthException;
use App\Services\Twitter\Support\Signature\HmacSha1;
use App\Services\Twitter\Support\Signature\Signature;
use App\Services\Twitter\Support\Token;
use App\Services\Twitter\Support\Util;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class TwitterRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected array $parameters = [];

    protected Method $method = Method::GET;

    public function url(): string
    {
        return sprintf(
            '%s/%s',
            rtrim(TwitterConnector::baseUrl(), '/'),
            ltrim($this->resolveEndpoint(), '/'),
        );
    }

    public function resolveEndpoint(): string
    {
        return '/example';
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function withConsumerAndToken(Consumer $consumer, Token $token): self
    {
        $defaults = [
            'oauth_version' => '1.0',
            'oauth_nonce' => Signature::generateNonce(),
            'oauth_timestamp' => time(),
            'oauth_consumer_key' => $consumer->key,
        ];

        $defaults['oauth_token'] = $token->key;

        $this->parameters = $defaults;

        return $this;
    }

    public function withSignature(Signature $signature): self
    {
        $this->setParameter(
            'oauth_signature',
            $signature->value(),
        );

        return $this;
    }

    public function buildSignature(Consumer $consumer, Token $token): Signature
    {
        $this->setParameter(
            key: 'oauth_signature_method',
            value: 'HMAC-SHA1',
        );

        return HmacSha1::signRequest($this, $consumer, $token);
    }

    public function authorizationHeader(): string
    {
        $first = true;
        $out = 'Authorization: OAuth';

        foreach ($this->parameters as $k => $v) {
            if (! str_starts_with($k, 'oauth')) {
                continue;
            }

            if (is_array($v)) {
                throw new TwitterOAuthException(
                    'Arrays not supported in headers',
                );
            }
            $out .= $first ? ' ' : ', ';
            $out .=
                Util::urlencodeRfc3986($k).
                '="'.
                Util::urlencodeRfc3986($v).
                '"';
            $first = false;
        }

        return $out;
    }

    private function setParameter(string $key, string $value)
    {
        $this->parameters[$key] = $value;
    }
}
