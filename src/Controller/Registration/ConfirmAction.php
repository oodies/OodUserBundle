<?php

/*
 * This file is part of the OodUserBundle package.
 *
 * (c) Sébastien CHOMY <sebastien.chomy@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ood\UserBundle\Controller\Registration;

use Ood\UserBundle\Manager\UserManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Receive the confirmation token from user email provider, login the user.
 *
 * Class ConfirmAction.
 */
class ConfirmAction
{
    /** @var RouterInterface */
    private $router;

    /** @var SessionInterface */
    private $session;

    /** @var TokenStorageInterface */
    private $tokenStorage;

    /** @var UserManager */
    private $userManager;

    /**
     * RegisterAction constructor.
     *
     * @param RouterInterface       $router
     * @param UserManager           $userManager
     * @param SessionInterface      $session
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        RouterInterface $router,
        UserManager $userManager,
        SessionInterface $session,
        TokenStorageInterface $tokenStorage
    ) {
        $this->router = $router;
        $this->session = $session;
        $this->userManager = $userManager;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param string $token
     *
     * @throws NotFoundHttpException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \InvalidArgumentException
     * @throws \Symfony\Component\Routing\Exception\InvalidParameterException
     * @throws \Symfony\Component\Routing\Exception\MissingMandatoryParametersException
     * @throws \Symfony\Component\Routing\Exception\RouteNotFoundException
     *
     * @return RedirectResponse
     */
    public function __invoke(string $token): RedirectResponse
    {
        $user = $this->userManager->findByConfirmationToken($token);

        if (null === $user) {
            throw new NotFoundHttpException(
                sprintf('The user with "confirmation token" does not exist for value "%s"', $token)
            );
        }

        $this->session->set('ood_user_registration/token', $token);

        $this->userManager->confirm($user);

        // auto authenticate
        $usernamePasswordToken = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $this->tokenStorage->setToken($usernamePasswordToken);
        $this->session->set('_security_main', serialize($usernamePasswordToken));

        // Forward next step
        return new RedirectResponse($this->router->generate('ood_user_registration_confirmed'));
    }
}
