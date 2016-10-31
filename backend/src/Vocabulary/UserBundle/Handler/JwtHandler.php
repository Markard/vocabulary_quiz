<?php

declare(strict_types = 1);

namespace Vocabulary\UserBundle\Handler;

use DateTime;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Psr\Cache\CacheItemPoolInterface;
use Vocabulary\UserBundle\Entity\User;

class JwtHandler
{
    /**
     * @var JWTTokenManagerInterface
     */
    protected $jwtManager;

    /**
     * @var int seconds
     */
    protected $tokenTtl;

    /**
     * @var CacheItemPoolInterface
     */
    protected $cache;

    public function __construct(CacheItemPoolInterface $cache, JWTTokenManagerInterface $jwtManager, int $tokenTtl)
    {
        $this->cache = $cache;
        $this->jwtManager = $jwtManager;
        $this->tokenTtl = $tokenTtl;
    }

    public function getJwtToken(User $user)
    {
        return $this->jwtManager->create($user);
    }
}