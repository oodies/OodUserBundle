<?php
/**
 * This file is part of UserBundle project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/11
 */

namespace Ood\UserBundle\Tests\Controller\Security;

use PHPUnit\Framework\TestCase;

/**
 * Class LoginActionWebTest
 *
 * @package Ood\UserBundle\tests\Controller\Security
 */
class LoginActionWebTest extends TestCase
{

    /** @var Symfony\Component\Security\Http\Authentication\AuthenticationUtils */
    private $authenticationUtils;

    /** @var Symfony\Component\Form\FormFactoryInterface */
    private $formFactory;

    /** @var RouterInterface */
    private $router;

    /** @var Twig */
    private $twig;

    public function setUp()
    {
       $this->authenticationUtils = $this->getMockBuilder('Symfony\Component\Security\Http\Authentication\AuthenticationUtils')->getMock();
       $this->formFactory = $this->getMockBuilder('Symfony\Component\Form\FormFactoryInterface')->getMock();
    }
}
