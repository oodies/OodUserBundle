<?php

/*
 * This file is part of the OodUserBundle package.
 *
 * (c) Sébastien CHOMY <sebastien.chomy@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ood\UserBundle\Entity;

use Ood\UserBundle\Model\User as AbstractUser;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class User.
 *
 * @UniqueEntity(
 *     fields={
 *         "username"
 *     },
 *     message="user.username.unique_entity",
 *     groups={"registration", "edit"}
 * )
 *
 * @UniqueEntity(
 *     fields={
 *         "email"
 *     },
 *     message="user.email.unique_entity",
 *     groups={"registration", "edit"}
 * )
 */
class User extends AbstractUser
{
}
