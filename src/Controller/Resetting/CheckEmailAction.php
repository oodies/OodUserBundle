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

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment as Twig;

/**
 * Tell the user to check his email provider.
 *
 * Class CheckEmailAction.
 */
class CheckEmailAction
{
    /** @var RouterInterface */
    private $router;

    /** @var SessionInterface */
    private $session;

    /** @var Twig */
    private $twig;

    /**
     * CheckEmailAction constructor.
     *
     * @param RouterInterface  $router
     * @param SessionInterface $session
     * @param Twig             $twig
     */
    public function __construct(
        RouterInterface $router,
        SessionInterface $session,
        Twig $twig
    ) {
        $this->router = $router;
        $this->session = $session;
        $this->twig = $twig;
    }

    /**
     * @param Request $request
     *
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
    public function __invoke(Request $request): Response
    {
        $username = $request->get('username');
        if (empty($username)) {
            // the user does not come from the sendEmail action
            return new RedirectResponse($this->router->generate('ood_user_resetting_request'));
        }

        $email = $this->session->get('ood_user_resetting/email');
        $this->session->remove('ood_user_resetting/email');

        return new Response(
            $this->twig->render('@OodUser/Resetting/check_email.html.twig', ['step' => 2, 'email' => $email])
        );
    }
}
