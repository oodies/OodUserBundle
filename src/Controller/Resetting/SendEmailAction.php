<?php

/*
 * This file is part of the OodUserBundle package.
 *
 * (c) Sébastien CHOMY <sebastien.chomy@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ood\UserBundle\Controller\Resetting;

use Ood\UserBundle\Manager\UserManager;
use Ood\UserBundle\Services\Messaging;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

/**
 * Request reset user password: submit form and send email.
 *
 * Class SendEmailAction.
 */
class SendEmailAction
{
    /** @var Messaging */
    private $messaging;

    /** @var RouterInterface */
    private $router;

    /** @var SessionInterface */
    private $session;

    /** @var UserManager */
    private $userManager;

    /**
     * SendEmailAction constructor.
     *
     * @param Messaging        $messaging
     * @param RouterInterface  $router
     * @param SessionInterface $session
     * @param UserManager      $userManager
     */
    public function __construct(
        Messaging $messaging,
        RouterInterface $router,
        SessionInterface $session,
        UserManager $userManager
    ) {
        $this->messaging = $messaging;
        $this->router = $router;
        $this->session = $session;
        $this->userManager = $userManager;
    }

    /**
     * @param Request $request
     *
     * @throws NotFoundHttpException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \InvalidArgumentException
     * @throws \Symfony\Component\Routing\Exception\InvalidParameterException
     * @throws \Symfony\Component\Routing\Exception\MissingMandatoryParametersException
     * @throws \Symfony\Component\Routing\Exception\RouteNotFoundException
     * @throws \Throwable
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     *
     * @return RedirectResponse
     */
    public function __invoke(Request $request): RedirectResponse
    {
        $username = $request->get('username');

        $user = $this->userManager->loadUserByUsername($username);

        if (null === $user) {
            throw new NotFoundHttpException(
                sprintf('The user with username "%s" does not exist', $username)
            );
        }
        $this->userManager->confirmationToken($user);
        $this->messaging->passwordResettingRequest($user);

        $this->session->set('ood_user_resetting/email', $user->getEmail());

        // Forward next step
        return new RedirectResponse(
            $this->router->generate('ood_user_resetting_check_email', ['username' => $username])
        );
    }
}
