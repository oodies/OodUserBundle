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

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;
use Twig\Environment as Twig;

/**
 * Tell the user his account is now confirmed.
 *
 * Class Confirmed.
 */
class ConfirmedAction
{
    /** @var RouterInterface */
    private $router;

    /** @var SessionInterface */
    private $session;

    /** @var TokenStorageInterface */
    private $tokenStorage;

    /** @var Twig */
    private $twig;

    /**
     * ConfirmedAction constructor.
     *
     * @param RouterInterface       $router
     * @param SessionInterface      $session
     * @param TokenStorageInterface $tokenStorage
     * @param Twig                  $twig
     */
    public function __construct(
        RouterInterface $router,
        SessionInterface $session,
        TokenStorageInterface $tokenStorage,
        Twig $twig
    ) {
        $this->router = $router;
        $this->session = $session;
        $this->tokenStorage = $tokenStorage;
        $this->twig = $twig;
    }

    /**
     * @throws AccessDeniedException
     * @throws \InvalidArgumentException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     *
     * @return Response
     */
    public function __invoke(): Response
    {
        if (!$this->session->has('ood_user_registration/token')) {
            throw new AccessDeniedException('This user does not have access to this section');
        }

        $user = $this->tokenStorage->getToken()->getUser();

        if (!\is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        return new Response($this->twig->render(
            '@OodUser/Registration/confirmed.html.twig',
            ['user' => $user]
        ));
    }
}
