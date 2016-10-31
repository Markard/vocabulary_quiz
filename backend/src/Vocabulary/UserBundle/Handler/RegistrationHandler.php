<?php

declare(strict_types = 1);

namespace Vocabulary\UserBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Vocabulary\UserBundle\Entity\User;

class RegistrationHandler
{
    /**
     * @var ObjectManager
     */
    protected $om;

    /**
     * @var UserProviderInterface
     */
    protected $repository;

    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ObjectManager $om, ContainerInterface $container)
    {
        $this->om = $om;
        $this->repository = $om->getRepository(User::class);
        $this->container = $container;
    }

    public function register(User $_user): User
    {
        try {
            $user = $this->repository->loadUserByUsername($_user->getUsername());
        } catch (UsernameNotFoundException $e) {
            $this->om->persist($_user);
            $this->om->flush($_user);
            $user = $_user;
        }

        $jwtToken = $this->container->get('vocabulary.jwt.handler')->getJwtToken($user);
        $user->setToken($jwtToken);

        return $user;
    }
}