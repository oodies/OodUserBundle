<?php
/**
 * This file is part of bundleUser project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/11
 */

namespace Ood\UserBundle\Tests;

use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Trait ContextTestTrait
 *
 * @package Ood\UserBundle\Tests
 */
trait ContextTestTrait
{
    /** *******************************
     *  PROPERTIES
     */

    /** @var \Symfony\Bundle\FrameworkBundle\Client|null $client */
    private $client = null;

    /** @var \Doctrine\ORM\EntityManagerInterface|null $em */
    private $em = null;



    /** *******************************
     *  METHODS
     */

    /**
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     */
    private function withAuthenticatedUser()
    {
        /** @var \Symfony\Component\HttpFoundation\Session\Session $session */
        $session = $this->client->getContainer()->get('session');
        $user = $this->em->getRepository('OodUserBundle:User')->findOneBy(['username' => 'username']);


        // the firewall context (defaults to the firewall name)
        $firewall = 'main';

        $token = new UsernamePasswordToken($user, '12345', $firewall, ['ROLE_USER']);
        $session->set('_security_' . $firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}
