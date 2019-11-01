<?php

namespace Yiisoft\Yii\Web\Tests\Auth;

use Nyholm\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Yii\Web\Auth\HttpBasicAuth;

class HttpBasicAuthTest extends AuthTest
{
    private const CorrectCredentials = ['user', 'password'];

    /**
     * @test
     */
    public function shouldAuthenticateToken()
    {
        $request = $this->getRequest('userToken');
        $auth = new HttpBasicAuth($this->getIdentityRepositoryWithIdentity());

        $this->assertSame($this->identity, $auth->authenticate($request));
    }

    /**
     * @test
     */
    public function shouldNotAuthenticateBadToken()
    {
        $request = $this->getRequest('userToken');
        $auth = new HttpBasicAuth($this->getIdentityRepositoryWithoutIdentity());

        $this->assertNull($auth->authenticate($request));
    }

    /**
     * @test
     */
    public function shouldNotAuthenticateWithoutUserOrToken()
    {
        $request = $this->getRequest();
        $auth = new HttpBasicAuth($this->getIdentityRepositoryWithoutIdentity());

        $this->assertNull($auth->authenticate($request));
    }

    /**
     * @test
     */
    public function shouldAuthenticateWithCredentials()
    {
        $request = $this->getRequest(...$this::CorrectCredentials);

        $auth = new HttpBasicAuth($this->getIdentityRepositoryWithoutIdentity());
        $auth->setAuth($this->getAuth());

        $this->assertSame($this->identity, $auth->authenticate($request));
    }

    /**
     * @test
     */
    public function shouldNotAuthenticateWithBadCredentials()
    {
        $request = $this->getRequest('user', 'wrongPass');

        $auth = new HttpBasicAuth($this->getIdentityRepositoryWithoutIdentity());
        $auth->setAuth($this->getAuth());

        $this->assertNull($auth->authenticate($request));
    }

    public function testChallenge()
    {
        $realm = 'private';
        $response = new Response();
        $auth = new HttpBasicAuth($this->getIdentityRepositoryWithoutIdentity());
        $auth->setRealm($realm);

        $responseWithChallenge = $auth->challenge($response);
        $this->assertTrue($responseWithChallenge->hasHeader('WWW-Authenticate'));
        $this->assertSame("Basic realm=\"{$realm}\"", $responseWithChallenge->getHeaderLine('WWW-Authenticate'));
    }

    /**
     * @param null $username
     * @param null $password
     * @return ServerRequestInterface|MockObject
     */
    private function getRequest($username = null, $password = null)
    {
        $request = $this->createMock(ServerRequestInterface::class);

        if ($username !== null) {
            $request->method('getServerParams')->willReturn([
                'PHP_AUTH_USER' => $username,
                'PHP_AUTH_PW' => $password,
            ]);
        }

        return $request;
    }

    private function getAuth()
    {
        return function ($user, $pass) {
            return [$user, $pass] === $this::CorrectCredentials
                ? $this->identity
                : null;
        };
    }
}
