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
 * Class LoginActionWebTest
 *
 * @package Ood\UserBundle\tests\Controller\Security
 */
class LoginActionWebTest extends WebTestCase
{
    use ContextTestTrait;

    /**
     *
     */
    public function setUp()
    {
        $this->client = static::CreateClient();
        $this->em = static::$kernel->getContainer()->get('doctrine')->getManager();
    }

    /**
     * Feature: Login action with authenticated user
     *  Scenario: nominal
     *      Given: user authenticated
     *      When: I am on "/login"
     *      Then: I redirect to "/"
     */
    public function testLoginWithAuthenticatedUser()
    {
        $this->withAuthenticatedUser();
        $this->client->request('GET', '/login');
        $this->assertTrue($this->client->getResponse()->isRedirect('/'));
    }

    /**
     * Feature: Sign in as a user
     *  Scenario : Nominal, with valid credentials
     *      Given: I am on "/login"
     *      When: I fill "_username" with "username"
     *      And: I fill "_password" with "12345"
     *      And: I press submit
     *      Then: I redirect to "/"
     *      And: I should see ....
     */
    public function testSignInAsUserWithValidCredentials()
    {
        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->selectButton('submit')->form();
        $form->setValues(
            [
                '_username' => 'username',
                '_password' => '12345'
            ]
        );
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        // TODO assert testing
        $this->assertNull(null);
    }

    /**
     * Feature: Sign in as a user
     *  Scenario  With invalid credentials
     *      Given: I am on "/login"
     *      When: I fill "_username" with "qwerty"
     *      And: I fill "_password" with "password"
     *      And: I press submit
     *      Then: I redirect to "/login"
     *      And: I should see "Invalid credentials"
     */
    public function testSignInAsUserWithInvalidCredentials()
    {
        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->selectButton('submit')->form();
        $form->setValues(
            [
                '_username' => 'qwerty',
                '_password' => 'password'
            ]
        );
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        $this->assertSame(1, $crawler->filter('div.error')->count());
    }

    /**
     *
     * Feature: Sign in as a user
     *  Scenario  With invalid credentials
     *      Given: I am on "/login"
     *      When: I fill "_username" with ""
     *      And: I fill "_password" with ""
     *      And: I press submit
     *      Then: I redirect to "/login"
     *      And: I should see "Invalid credentials"
     */
    public function testSignInWithoutCredentials()
    {
        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->selectButton('submit')->form();
        $form->setValues(
            [
                '_username' => '',
                '_password' => ''
            ]
        );
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        $this->assertSame(1, $crawler->filter('div.error')->count());
    }
}
