<?php

/*
 * This file is part of the OodUserBundle package.
 *
 * (c) Sébastien CHOMY <sebastien.chomy@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ood\UserBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Ood\UserBundle\Entity\User;
use Ood\UserBundle\Repository\UserRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserManager.
 */
class UserManager
{
    /**
     * @var UserRepository
     */
    protected $repository;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var UserPasswordEncoderInterface
     */
    protected $encoder;

    /**
     * UserManager constructor.
     *
     * @param EntityManagerInterface       $em
     * @param UserRepository               $userRepository
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(
        EntityManagerInterface $em,
        UserRepository $userRepository,
        UserPasswordEncoderInterface $encoder
    ) {
        $this->em = $em;
        $this->repository = $userRepository;
        $this->encoder = $encoder;
    }

    /**
     * @param $token
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return mixed
     */
    public function findByConfirmationToken($token)
    {
        return $this->repository->findByConfirmationToken($token);
    }

    /**
     * @return array
     */
    public function findAll()
    {
        return $this->repository->findAll();
    }

    /**
     * @param User $user
     *
     * @throws \Exception
     */
    public function register(User $user)
    {
        $user->setPassword($this->encoder->encodePassword($user, $user->getPlainPassword()))
            ->setConfirmationToken($this->getToken())
        ;
        $user->eraseCredentials();
        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * @param $email
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return null|User
     */
    public function loadUserByUsername($email): ?User
    {
        return $this->repository->loadUserByUsername($email);
    }

    /**
     * @param User $user
     */
    public function confirm(User $user)
    {
        $user->setConfirmationToken(null)
            ->setLocked(false)
        ;
        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * @param User $user
     */
    public function changePassword(User $user)
    {
        $user->setPassword($this->encoder->encodePassword($user, $user->getPlainPassword()))
            ->setConfirmationToken(null)
        ;
        $user->eraseCredentials();
        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * @param User $user
     *
     * @throws \Exception
     */
    public function confirmationToken(User $user)
    {
        $user->setConfirmationToken($this->getToken());
        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * @param User $user
     */
    public function lock(User $user)
    {
        $user->setUpdateAt(new \DateTime())
            ->setLocked(true)
        ;
        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * @param User $user
     */
    public function unlock(User $user)
    {
        $user->setUpdateAt(new \DateTime())
            ->setLocked(false)
        ;
        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * @param User $user
     */
    public function update(User $user)
    {
        $user->setUpdateAt(new \DateTime());
        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * @throws \Exception
     *
     * @return string
     */
    protected function getToken()
    {
        return rtrim(strtr(base64_encode(random_bytes(20)), '+/', '-_'), '=');
    }
}
