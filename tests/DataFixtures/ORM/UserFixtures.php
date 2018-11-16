<?php

/*
 * This file is part of the OodUserBundle package.
 *
 * (c) Sébastien CHOMY <sebastien.chomy@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ood\UserBundle\Tests\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Ood\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

/**
 * Class UserFixtures.
 */
class UserFixtures extends Fixture implements ContainerAwareInterface
{
    /**
     * The dependency injection container.
     *
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var UserPasswordEncoder
     */
    protected $encoder;

    /**
     * @param null|ContainerInterface $container
     *
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     *
     * @return void
     */
    public function setContainer(ContainerInterface $container = null): void
    {
        $this->container = $container;
        $this->encoder = $this->container->get('security.password_encoder');
    }

    /**
     * @param ObjectManager $manager
     *
     * @throws \BadMethodCallException
     *
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user
            ->setEmail('username@email.com')
            ->setUsername('username@email.com')
            ->setRoles(['ROLE_USER'])
        ;

        $hashPassword = $this->encoder->encodePassword($user, '12345');
        $user->setPassword($hashPassword);

        $this->addReference('user', $user);

        $manager->persist($user);
        $manager->flush();
    }
}
