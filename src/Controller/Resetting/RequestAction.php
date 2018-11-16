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

use Ood\UserBundle\Entity\User;
use Ood\UserBundle\Form\ResettingRequestForm;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment as Twig;

/**
 * Request reset user password: show form.
 *
 * Class RequestAction.
 */
class RequestAction
{
    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var RouterInterface */
    private $router;

    /** @var Twig */
    private $twig;

    /**
     * Request constructor.
     *
     * @param FormFactoryInterface $formFactory
     * @param RouterInterface      $router
     * @param Twig                 $twig
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        RouterInterface $router,
        Twig $twig
    ) {
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->twig = $twig;
    }

    /**
     * @param Request $request
     *
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
     * @return mixed
     */
    public function __invoke(Request $request): Response
    {
        $user = new User();
        $form = $this->formFactory->create(ResettingRequestForm::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            return new RedirectResponse(
                $this->router->generate(
                    'ood_user_resetting_send_email',
                    ['username' => $user->getUsername()]
                )
            );
        }

        return new Response(
            $this->twig->render(
                '@OodUser/Resetting/request.html.twig',
                [
                    'form' => $form->createView(),
                    'step' => 1,
                ]
            )
        );
    }
}
