<?php

/*
 * This file is part of the OodUserBundle package.
 *
 * (c) Sébastien CHOMY <sebastien.chomy@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ood\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class LoginForm.
 */
class LoginForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                '_username',
                TextType::class,
                [
                    'label'    => 'login.username.label',
                    'required' => true,
                ]
            )
            ->add(
                '_password',
                PasswordType::class,
                [
                    'label'    => 'login.password.label',
                    'required' => true,
                ]
            )
            ->add(
                '_remember_me',
                CheckboxType::class,
                [
                    'mapped'   => false,
                    'label'    => 'login.remember_me.label',
                    'required' => false,
                ]
            )
        ;
    }

    /**
     * @param OptionsResolver $resolver
     *
     * @throws \Symfony\Component\OptionsResolver\Exception\AccessException
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'allow_extra_fields' => true,
                'translation_domain' => 'application',
                'attr'               => [
                    'name' => 'form_login',
                    'id'   => 'form_login',
                ],
            ]
        );
    }

    /**
     * @return null|string
     */
    public function getBlockPrefix()
    {
        return '';
    }
}
