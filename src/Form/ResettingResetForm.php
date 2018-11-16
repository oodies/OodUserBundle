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

use Ood\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ResettingResetForm.
 */
class ResettingResetForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'plainPassword',
                RepeatedType::class,
                [
                    'type'          => PasswordType::class,
                    'required'      => true,
                    'first_options' => [
                        'label' => 'resetting_reset.plain_password.label',
                    ],
                    'second_options' => [
                        'label' => 'resetting_reset.plain_password_repeat.label',
                    ],
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
                'data_class'         => User::class,
                'translation_domain' => 'application',
                'validation_groups'  => [$this, 'getValidationGroups'],
            ]
        );
    }

    /**
     * Obtain validation groups according to data form.
     *
     * @return array
     */
    public function getValidationGroups(): array
    {
        return ['resettingReset'];
    }
}
