<?php

namespace App\Form;

use App\Entity\User;
use DateTimeImmutable;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Usuario',
                'label_attr' => ['class' => "form-label"],
                'attr' => ['class' => "form-control"],
                'row_attr' => ['style' => 'position: relative', 'class' => 'col-md-6']
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Nombres',
                'label_attr' => ['class' => "form-label"],
                'attr' => ['class' => "form-control"],
                'row_attr' => ['style' => 'position: relative', 'class' => 'col-md-6']
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Apellidos',
                'label_attr' => ['class' => "form-label"],
                'attr' => ['class' => "form-control"],
                'row_attr' => ['style' => 'position: relative', 'class' => 'col-md-6']
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Contraseña',
                'label_attr' => ['class' => "form-label"],
                'attr' => ['class' => "form-control"],
                'row_attr' => ['style' => 'position: relative', 'class' => 'col-md-6']
                ])
            ->add('created_at', null, [
                'widget' => 'single_text',
                'label' => 'Fecha',
                'label_attr' => ['class' => "form-label"],
                'attr' => ['class' => "form-control"],
                'row_attr' => ['style' => 'position: relative', 'class' => 'col-md-6', 'hidden' => true],
                'data' => new DateTimeImmutable()
            ])
            ->add('updated_at', null, [
                'widget' => 'single_text',
                'label' => 'Fecha',
                'label_attr' => ['class' => "form-label"],
                'attr' => ['class' => "form-control"],
                'row_attr' => ['style' => 'position: relative', 'class' => 'col-md-6', 'hidden' => true],
                'data' => new DateTimeImmutable()
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'attr' => ['class' => 'row']
        ]);
    }
}
