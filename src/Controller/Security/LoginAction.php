<?php

/*
 * This file is part of the OodUserBundle package.
 *
 * (c) Sébastien CHOMY <sebastien.chomy@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ood\UserBundle\Controller\Security;

use Ood\UserBundle\Form\LoginForm;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment as Twig;

/**
 * Class LoginAction.
 */
final class LoginAction
{
    /** @var AuthenticationUtils */
    private $authenticationUtils;

    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var RouterInterface */
    private $router;

    /** @var Twig */
    private $twig;

    /**
     * LoginAction constructor.
     *
     * @param AuthenticationUtils  $authenticationUtils
     * @param FormFactoryInterface $formFactory
     * @param RouterInterface      $router
     * @param Twig                 $twig
     */
    public function __construct(
        AuthenticationUtils $authenticationUtils,
        FormFactoryInterface $formFactory,
        RouterInterface $router,
        Twig $twig
    ) {
        $this->authenticationUtils = $authenticationUtils;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->twig = $twig;
    }

    /**
     * @param UserInterface $user
     *
     * @throws \InvalidArgumentException
     * @throws \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     * @throws \Symfony\Component\Routing\Exception\InvalidParameterException
     * @throws \Symfony\Component\Routing\Exception\MissingMandatoryParametersException
     * @throws \Symfony\Component\Routing\Exception\RouteNotFoundException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     *
     * @return Response
     */
    public function __invoke(Request $request, UserInterface $user = null): Response
    {
        if (null !== $user) {
            return new RedirectResponse($this->router->generate('index'));
        }

        $form = $this->formFactory->create(
            LoginForm::class,
            // last username entered by the user
            ['_username' => $this->authenticationUtils->getLastUsername()]
        );

        if ($request->isXmlHttpRequest()) {
            $templating = '@OodUser/Security/login_content.html.twig';
        } else {
            $templating = '@OodUser/Security/login.html.twig';
        }

        return new Response(
            $this->twig->render(
                $templating,
                [
                    'form' => $form->createView(),
                    // get the login error if there is one
                    'error' => $this->authenticationUtils->getLastAuthenticationError(),
                ]
            )
        );
    }
}
