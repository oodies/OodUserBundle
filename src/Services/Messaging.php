<?php

/*
 * This file is part of the OodUserBundle package.
 *
 * (c) Sébastien CHOMY <sebastien.chomy@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ood\UserBundle\Services;

use Ood\UserBundle\Entity\User;
use Swift_Mailer;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig_Environment;

/**
 * Class Messaging.
 */
class Messaging
{
    const SENDER_NAME = 'oodigital Team';

    const SENDER_NO_REPLY = 'noreply@oodigital.fr';

    // ---------------------
    // PROTECTED MEMBERS
    // ---------------------

    /** @var Swift_Mailer */
    protected $mailer;

    /** @var Twig_Environment */
    protected $twig;

    /** @var UrlGeneratorInterface */
    protected $router;

    /**
     * Messaging constructor.
     *
     * @param Swift_Mailer          $mailer
     * @param Twig_Environment      $twig
     * @param UrlGeneratorInterface $router
     */
    public function __construct(
        Swift_Mailer $mailer,
        Twig_Environment $twig,
        UrlGeneratorInterface $router
    ) {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->router = $router;
    }

    /**
     * Send an email to confirm user registration.
     *
     * @param User $user
     *
     * @throws \Symfony\Component\Routing\Exception\InvalidParameterException
     * @throws \Symfony\Component\Routing\Exception\MissingMandatoryParametersException
     * @throws \Symfony\Component\Routing\Exception\RouteNotFoundException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws \Throwable
     * @throws \Throwable
     */
    public function confirmationRegistrationRequest(User $user)
    {
        $template = $this->twig->load('@OodUser/Registration/email.html.twig');

        $urlConfirmation = $this->router->generate(
            'ood_user_registration_confirm',
            ['token' => $user->getConfirmationToken()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        $message = (new \Swift_Message())
            ->setSubject($template->renderBlock('subject', ['username' => $user->getUsername()]))
            ->setContentType('text/html')
            ->setFrom(self::SENDER_NO_REPLY, self::SENDER_NAME)
            ->setTo($user->getEmail())
            ->setBody(
                $template->renderBlock(
                    'body_html',
                    [
                        'urlConfirmation' => $urlConfirmation,
                        'username'        => $user->getUsername(),
                    ]
                )
            )
        ;
        $this->mailer->send($message);
    }

    /**
     * Send email for password resetting request.
     *
     * @param User $user
     *
     * @throws \Symfony\Component\Routing\Exception\InvalidParameterException
     * @throws \Symfony\Component\Routing\Exception\MissingMandatoryParametersException
     * @throws \Symfony\Component\Routing\Exception\RouteNotFoundException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws \Throwable
     * @throws \Throwable
     */
    public function passwordResettingRequest(User $user)
    {
        $template = $this->twig->load('@OodUser/Resetting/email.html.twig');
        $urlConfirmation = $this->router->generate(
            'ood_user_resetting_reset',
            ['token' => $user->getConfirmationToken()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        $message = (new \Swift_Message())
            ->setSubject($template->renderBlock('subject'))
            ->setContentType('text/html')
            ->setFrom(self::SENDER_NO_REPLY, self::SENDER_NAME)
            ->setTo($user->getEmail())
            ->setBody(
                $template->renderBlock(
                    'body_html',
                    [
                        'urlConfirmation' => $urlConfirmation,
                        'username'        => $user->getUsername(),
                    ]
                )
            )
        ;
        $this->mailer->send($message);
    }
}
