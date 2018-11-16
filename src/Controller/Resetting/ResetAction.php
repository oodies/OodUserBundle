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

use Ood\UserBundle\Form\ResettingResetForm;
use Ood\UserBundle\Manager\UserManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment as Twig;

/**
 * Class ResetAction.
 */
class ResetAction
{
    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var RouterInterface */
    private $router;

    /** @var Twig */
    private $twig;

    /** @var UserManager */
    private $userManager;

    /**
     * ResetAction constructor.
     *
     * @param FormFactoryInterface $formFactory
     * @param RouterInterface      $router
     * @param Twig                 $twig
     * @param UserManager          $userManager
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        RouterInterface $router,
        Twig $twig,
        UserManager $userManager
    ) {
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->twig = $twig;
        $this->userManager = $userManager;
    }

    /**
     * ResetAction user password.
     *
     * @param string  $token
     * @param Request $request
     *
     * @throws NotFoundHttpException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \InvalidArgumentException
     * @throws \Symfony\Component\Form\Exception\LogicException
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
    public function __invoke(string $token, Request $request): Response
    {
        $user = $this->userManager->findByConfirmationToken($token);
        if (null === $user) {
            throw new NotFoundHttpException(
                sprintf('The user with "confirmation token" does not exist for value "%s"', $token)
            );
        }
        $form = $this->formFactory->create(ResettingResetForm::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->userManager->changePassword($user);

            return new RedirectResponse($this->router->generate('ood_user_security_login'));
        }

        return new Response(
            $this->twig->render(
                '@OodUser/Resetting/reset.html.twig',
                [
                    'token' => $token,
                    'form'  => $form->createView(),
                    'step'  => 3,
                ]
            )
        );
    }
}
