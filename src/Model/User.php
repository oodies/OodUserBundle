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

/**
 * Class User.
 */
abstract class User implements UserInterface
{
    /**
     * Contains the ID of the user.
     *
     * @var null|int
     */
    protected $id;

    /**
     * Contains the username (alias) of the person.
     *
     * @var string
     */
    protected $username;

    /**
     * Contains the email address of the person.
     *
     * @var string
     */
    protected $email;

    /**
     * Encrypted password. Must be persisted.
     *
     * @var string
     */
    protected $password;

    /**
     * Plain password. Used for model validation. Must not be persisted.
     *
     * @var string
     */
    protected $plainPassword;

    /**
     * Random string sent to the user email address in order to verify it.
     *
     * @var null|string
     */
    protected $confirmationToken;

    /**
     * Date of registration.
     *
     * @var \DateTime
     */
    protected $registeredAt;

    /**
     * Last update data.
     *
     * @var \DateTime
     */
    protected $updateAt;

    /**
     * user is locked by an administrator.
     *
     * @var boolean
     */
    protected $locked;

    /**
     * user is active ?
     * Depending on whether the user's registration
     * has been validated or not (in the case of a confirmation by email for example).
     *
     * @var boolean
     */
    protected $isActive = true;

    /**
     * Role of the user.
     *
     * @var array
     */
    protected $roles = [];

    /**
     * @var string
     */
    protected $locale;

    /**
     * @var \DateTime
     */
    protected $lastAction;

    /**
     * UserBundle constructor.
     */
    public function __construct()
    {
        $dateAt = new \DateTime();
        $this
            ->setRegisteredAt($dateAt)
            ->setUpdateAt($dateAt)
            ->setLocked(false)
            ->setRoles([self::ROLE_DEFAULT])
        ;
    }

    /**
     * @return null|int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param null|string $username
     *
     * @return User
     */
    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param null|string $email
     *
     * @return User
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return User
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param null|string $plainPassword
     *
     * @return User
     */
    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    /**
     * @param null|string $confirmationToken
     *
     * @return User
     */
    public function setConfirmationToken(?string $confirmationToken): self
    {
        $this->confirmationToken = $confirmationToken;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getRegisteredAt(): \DateTime
    {
        return $this->registeredAt;
    }

    /**
     * @param \DateTime $registeredAt
     *
     * @return User
     */
    public function setRegisteredAt(\DateTime $registeredAt): self
    {
        $this->registeredAt = $registeredAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdateAt(): \DateTime
    {
        return $this->updateAt;
    }

    /**
     * @param \DateTime $updateAt
     *
     * @return User
     */
    public function setUpdateAt(\DateTime $updateAt): self
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    /**
     * @return bool
     */
    public function isLocked(): bool
    {
        return $this->locked;
    }

    /**
     * @param bool $locked
     *
     * @return User
     */
    public function setLocked(bool $locked): self
    {
        $this->locked = $locked;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     *
     * @return User
     */
    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

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
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     *
     * @return User
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     *
     * @return User
     */
    public function setLocale(string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLastAction(): \DateTime
    {
        return $this->lastAction;
    }

    /**
     * @param \DateTime $lastAction
     *
     * @return User
     */
    public function setLastAction(\DateTime $lastAction): self
    {
        $this->lastAction = $lastAction;

        return $this;
    }

    /**
     * String representation of object
     * \Serializable::serialize().
     */
    public function serialize()
    {
        return serialize(
            [
                $this->id,
                $this->username,
                $this->password,
                $this->isActive,
            ]
        );
    }

    /**
     * Constructs the object
     * \Serializable::unserialize().
     *
     * @param $serialized
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->password,
            $this->isActive
            ) = unserialize($serialized);
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return null|string The salt
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }
}
