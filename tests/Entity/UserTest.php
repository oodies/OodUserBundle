<?php
/**
 * This file is part of UserBundle project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/11
 */

namespace Ood\UserBundle\Tests\DataFixtures\Entity;

use Ood\UserBundle\Entity\User;
use PHPUnit\Framework\TestCase;

/**
 * Class UserTest
 */
class UserTest extends TestCase
{
    /** @var User */
    protected $user;


    /**
     * @throws InvalidArgumentException
     * @throws ReflectionException
     * @throws \PHPUnit\Framework\Exception
     */
    public function setUp()
    {
        $this->user = $this->getUser();
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testId()
    {
        $user = $this->getUser();
        $this->assertNull($user->getId());
    }

    /**
     * @throws InvalidArgumentException
     * @throws ReflectionException
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testUsername()
    {
        $user = $this->getUser();
        $this->assertNull($user->getUsername());

        $user->setUsername('tony');
        $this->assertEquals('tony', $user->getUsername());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\Exception
     * @throws \ReflectionException
     */
    protected function getUser()
    {
        return $this->getMockForAbstractClass('Ood\UserBundle\Entity\User');
    }
}
