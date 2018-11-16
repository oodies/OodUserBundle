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

use Ood\UserBundle\Entity\User;
use Ood\UserBundle\Form\RegistrationForm;
use Ood\UserBundle\Manager\UserManager;
use Ood\UserBundle\Services\Messaging;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment as Twig;

/**
 * Register reset user password: show form.
 *
 * Class RegisterAction.
 */
class RegisterAction
{
    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var Messaging */
    private $messaging;

    /** @var RouterInterface */
    private $router;

    /** @var SessionInterface */
    private $session;

    /** @var UserManager */
    private $userManager;

    /** @var Twig */
    private $twig;

    /**
     * RegisterAction constructor.
     *
     * @param FormFactoryInterface $formFactory
     * @param Messaging            $messaging
     * @param RouterInterface      $router
     * @param UserManager          $userManager
     * @param SessionInterface     $session
     * @param Twig                 $twig
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        Messaging $messaging,
        RouterInterface $router,
        UserManager $userManager,
        SessionInterface $session,
        Twig $twig
    ) {
        $this->formFactory = $formFactory;
        $this->messaging = $messaging;
        $this->router = $router;
        $this->session = $session;
        $this->userManager = $userManager;
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
     * @throws \Throwable
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     *
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $user = new User();
        $form = $this->formFactory->create(RegistrationForm::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->userManager->register($user);
            $this->messaging->confirmationRegistrationRequest($user);
            $this->session->set('ood_user_registration/email', $user->getEmail());

            // Forward next step
            return new RedirectResponse($this->router->generate('ood_user_registration_check_email'));
        }

        return new Response($this->twig->render(
            '@OodUser/Registration/register.html.twig',
            [
                'form'     => $form->createView(),
                'hasError' => (bool) \count($form->getErrors(true)),
            ]
        ));
    }
}
