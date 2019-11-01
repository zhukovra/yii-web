<?php

namespace Yiisoft\Yii\Web\Tests\Auth;

use PHPUnit\Framework\TestCase;
use Yiisoft\Yii\Web\User\IdentityInterface;
use Yiisoft\Yii\Web\User\IdentityRepositoryInterface;

abstract class AuthTest extends TestCase
{
    /** @var IdentityInterface */
    protected $identity;

    protected const CorrectToken = 'CorrectToken';

    protected function setUp()
    {
        $this->identity = $this->createMock(IdentityInterface::class);
    }

    protected function getIdentityRepository($token = null): IdentityRepositoryInterface
    {
        $identity = $token === $this::CorrectToken ? $this->identity : null;

        $repository = $this->createMock(IdentityRepositoryInterface::class);
        $repository->method('findIdentityByToken')->willReturn($identity);

        return $repository;
    }

    protected function getIdentityRepositoryWithIdentity(): IdentityRepositoryInterface
    {
        $repository = $this->createMock(IdentityRepositoryInterface::class);
        $repository->method('findIdentityByToken')->willReturn($this->identity);

        return $repository;
    }

    protected function getIdentityRepositoryWithoutIdentity(): IdentityRepositoryInterface
    {
        $repository = $this->createMock(IdentityRepositoryInterface::class);
        $repository->method('findIdentityByToken')->willReturn(null);

        return $repository;
    }
}
