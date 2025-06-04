<?php

namespace App\Http\Integrations\UserMicroservice;

use App\Http\Integrations\UserMicroservice\Requests\GetById;
use App\Http\Integrations\UserMicroservice\Requests\GetCurrent;
use Illuminate\Support\Facades\Cache;
use Saloon\CachePlugin\Contracts\Cacheable;
use Saloon\CachePlugin\Contracts\Driver;
use Saloon\CachePlugin\Drivers\LaravelCacheDriver;
use Saloon\CachePlugin\Traits\HasCaching;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Helpers\OAuth2\OAuthConfig;
use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Connector;
use Saloon\Http\Response;
use Saloon\Traits\OAuth2\AuthorizationCodeGrant;
use Saloon\Traits\Plugins\AcceptsJson;

class UserMicroserviceConnector extends Connector implements Cacheable
{
    use AuthorizationCodeGrant, AcceptsJson, HasCaching;

    public string $token;

    /**
     * The Base URL of the API.
     */
    public function resolveBaseUrl(): string
    {
        return 'http://users:8000/api/v1';
    }

    /**
     * Default headers for every request
     */
    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    protected function defaultAuth(): TokenAuthenticator
    {
        return new TokenAuthenticator($this->token);
    }

    /**
     * @return Response
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function getCurrent(): Response
    {
        return $this->send(new GetCurrent());
    }

    /**
     * @param int $userId
     * @return Response
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function getUserById(int $userId): Response
    {
        return $this->send(new GetById($userId));
    }

    /**
     * The OAuth2 configuration
     */
    protected function defaultOauthConfig(): OAuthConfig
    {
        return OAuthConfig::make()
            ->setClientId('')
            ->setClientSecret('')
            ->setRedirectUri('')
            ->setDefaultScopes([])
            ->setAuthorizeEndpoint('authorize')
            ->setTokenEndpoint('token')
            ->setUserEndpoint('user');
    }

    public function resolveCacheDriver(): Driver
    {
        return new LaravelCacheDriver(Cache::store('redis'));
    }

    public function cacheExpiryInSeconds(): int
    {
        return 15;
    }
}
