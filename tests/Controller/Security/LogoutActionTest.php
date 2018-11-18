<?php
/**
 * This file is part of UserBundle project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/11
 */

namespace Ood\UserBundle\Tests\Controller\Security;

use Ood\UserBundle\Tests\ContextTestTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class LogoutActionTest
 *
 * @package Ood\UserBundle\Tests\Controller\Security
 */
class LogoutActionTest extends WebTestCase
{
    use ContextTestTrait;

    /**
     * @throws \InvalidArgumentException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    public function setUp()
    {
        $this->client = static::CreateClient();
        $this->em = static::$kernel->getContainer()->get('doctrine')->getManager();
    }

    /**
     * Feature: Logout action
     *  Scenario #1: Nominal, with user authenticated
     *      Given: user authenticated
     *      When: I am on "/logout"
     *      Then: I redirect to "/login"
     *  Scenario #2: with user unauthenticated
     *      When: I am on "/logout"
     *      Then: I redirect to "/login"
     *
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \PHPUnit_Framework_Exception
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \UnexpectedValueException
     */
    public function testLogout()
    {
        // Scenario #1
        $this->withAuthenticatedUser();
        $this->client->request('GET', '/logout');
        $this->client->followRedirect();
        $this->assertRegExp('/\/login$/', $this->client->getResponse()->headers->get('location'));
        // Scenario 2
        $this->client->request('GET', '/logout');
        $this->client->followRedirect();
        $this->assertRegExp('/\/login$/', $this->client->getResponse()->headers->get('location'));
    }
}
