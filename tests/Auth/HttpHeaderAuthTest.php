<?php

namespace Yiisoft\Yii\Web\Tests\Auth;

use Nyholm\Psr7\ServerRequest;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Yii\Web\Auth\HttpHeaderAuth;
use PHPUnit\Framework\TestCase;
use Yiisoft\Yii\Web\User\IdentityInterface;
use Yiisoft\Yii\Web\User\IdentityRepositoryInterface;

class HttpHeaderAuthTest extends AuthTest
{
    private const KeyHeader = 'X-Key';
    private const KeyValue = 'ApiKey';

    /**
     * @test
     */
    public function shouldAuthenticateWithApiKey()
    {
        $request = $this->getRequest($this::KeyHeader, $this::KeyValue);
        $auth = new HttpHeaderAuth($this->getIdentityInterface($this::KeyHeader, $this::KeyValue));

        $this->assertSame($this->identity, $auth->authenticate($request));
    }

    /**
     * @test
     */
    public function shouldNotAuthenticateWithoutApiKey()
    {
        $keyValue = '';
        $request = $this->getRequest($this::KeyHeader, $keyValue);
        $auth = new HttpHeaderAuth($this->getIdentityInterface($this::KeyHeader, $keyValue));

        $this->assertNull($auth->authenticate($request));
    }

    private function getIdentityInterface(string $name = null, string $value = null): IdentityRepositoryInterface
    {
        return [$name, $value] === [$this::KeyHeader, $this::KeyValue]
            ? $this->getIdentityRepositoryWithIdentity()
            : $this->getIdentityRepositoryWithoutIdentity();
    }

    private function getRequest($headerName, $headerValue): ServerRequest
    {
        return new ServerRequest(200, '/', [$headerName => $headerValue]);
    }
}
