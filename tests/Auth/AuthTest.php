<?php

namespace Yiisoft\Yii\Web\Tests\Auth;

use PHPUnit\Framework\TestCase;
use Yiisoft\Yii\Web\User\IdentityInterface;
use Yiisoft\Yii\Web\User\IdentityRepositoryInterface;

abstract class AuthTest extends TestCase
{
    /** @var IdentityInterface */
    protected $identity;

    protected function setUp()
    {
        $this->identity = $this->createMock(IdentityInterface::class);
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
