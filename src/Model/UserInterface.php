<?php

/*
 * This file is part of the OodUserBundle package.
 *
 * (c) Sébastien CHOMY <sebastien.chomy@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ood\UserBundle\Model;

use Symfony\Component\Security\Core\User\UserInterface as BaseUserInterface;

/**
 * Class UserInterface.
 */
interface UserInterface extends BaseUserInterface, \Serializable
{
    /**
     * Default role.
     */
    const ROLE_DEFAULT = 'ROLE_USER';

    /**
     * @return null|int
     */
    public function getId(): ?int;

    /**
     * @return null|string
     */
    public function getUsername(): ?string;

    /**
     * @param null|string $username
     *
     * @return User
     */
    public function setUsername(?string $username);

    /**
     * @return null|string
     */
    public function getEmail(): ?string;

    /**
     * @param null|string $email
     *
     * @return User
     */
    public function setEmail(?string $email);

    /**
     * @return null|string
     */
    public function getPassword(): ?string;

    /**
     * @param string $password
     *
     * @return User
     */
    public function setPassword(string $password);

    /**
     * @return null|string
     */
    public function getPlainPassword(): ?string;

    /**
     * @param null|string $plainPassword
     *
     * @return User
     */
    public function setPlainPassword(?string $plainPassword);

    /**
     * @return null|string
     */
    public function getConfirmationToken(): ?string;

    /**
     * @param null|string $confirmationToken
     *
     * @return User
     */
    public function setConfirmationToken(?string $confirmationToken);

    /**
     * @return \DateTime
     */
    public function getRegisteredAt(): \DateTime;

    /**
     * @param \DateTime $registeredAt
     *
     * @return User
     */
    public function setRegisteredAt(\DateTime $registeredAt);

    /**
     * @return \DateTime
     */
    public function getUpdateAt(): \DateTime;

    /**
     * @param \DateTime $updateAt
     *
     * @return User
     */
    public function setUpdateAt(\DateTime $updateAt);

    /**
     * @return bool
     */
    public function isLocked(): bool;

    /**
     * @param bool $locked
     *
     * @return User
     */
    public function setLocked(bool $locked);

    /**
     * @return bool
     */
    public function isActive(): bool;

    /**
     * @param bool $isActive
     *
     * @return User
     */
    public function setIsActive(bool $isActive);

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return array The user roles
     */
    public function getRoles(): array;

    /**
     * @param array $roles
     *
     * @return User
     */
    public function setRoles(array $roles);

    /**
     * @return string
     */
    public function getLocale(): string;

    /**
     * @param string $locale
     *
     * @return User
     */
    public function setLocale(string $locale);

    /**
     * @return \DateTime
     */
    public function getLastAction(): \DateTime;

    /**
     * @param \DateTime $lastAction
     *
     * @return User
     */
    public function setLastAction(\DateTime $lastAction);

    /**
     * String representation of object
     * \Serializable::serialize().
     */
    public function serialize();

    /**
     * Constructs the object
     * \Serializable::unserialize().
     *
     * @param $serialized
     */
    public function unserialize($serialized);

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return null|string The salt
     */
    public function getSalt(): ?string;

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials();
}
