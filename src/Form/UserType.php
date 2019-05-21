<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'attr' => array('class' => 'form-control')
            ])
            ->add('firstname', TextType::class, [
                'attr' => array('class' => 'form-control')
            ])
            ->add('surname', TextType::class, [
                'attr' => array('class' => 'form-control')
            ])
            ->add('email', EmailType::class, [
                'attr' => array('class' => 'form-control')
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Password',
                    'attr' => array('class' => 'form-control'
                    )],
                'second_options' => [
                    'label' => 'Repated password',
                    'attr' => array('class' => 'form-control'
                    )]
            ])
            ->add('Register', SubmitType::class, [
                'attr' => array('class' => 'btn btn-success form-button-custom')
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
                                   'data_class' => User::class,
                               ]);
    }
}