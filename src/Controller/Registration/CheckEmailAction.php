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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment as Twig;

/**
 * Tell the user to check their email provider.
 *
 * Class CheckEmailAction.
 */
class CheckEmailAction
{
    /** @var RouterInterface */
    private $router;

    /** @var SessionInterface */
    private $session;

    /** @var UserManager */
    private $userManager;

    /** @var Twig */
    private $twig;

    /**
     * CheckEmailAction constructor.
     *
     * @param RouterInterface  $router
     * @param UserManager      $userManager
     * @param SessionInterface $session
     * @param Twig             $twig
     */
    public function __construct(
        RouterInterface $router,
        UserManager $userManager,
        SessionInterface $session,
        Twig $twig
    ) {
        $this->router = $router;
        $this->session = $session;
        $this->userManager = $userManager;
        $this->twig = $twig;
    }

    /**
     * @throws NotFoundHttpException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \InvalidArgumentException
     * @throws \Symfony\Component\Routing\Exception\InvalidParameterException
     * @throws \Symfony\Component\Routing\Exception\MissingMandatoryParametersException
     * @throws \Symfony\Component\Routing\Exception\RouteNotFoundException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     *
     * @return Response
     */
    public function __invoke(): Response
    {
        $email = $this->session->get('ood_user_registration/email');

        if (empty($email)) {
            return new RedirectResponse($this->router->generate('ood_user_registration_register'));
        }

        $this->session->remove('ood_user_registration/email');
        $user = $this->userManager->loadUserByUsername($email);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with email "%s" does not exist', $email));
        }

        return new Response(
            $this->twig->render(
                '@OodUser/Registration/check_email.html.twig',
                ['email' => $email]
            )
        );
    }
}
