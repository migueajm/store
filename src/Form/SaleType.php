<?php

namespace App\Form;

use App\Entity\Sale;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SaleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        //$currentUser = $options['current_user'];
        $builder
            ->add('total_amount')
            ->add('sale_date', null, [
                'widget' => 'single_text',
            ])
            ->add('payment_method')
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
                //'choices' => [$currentUser]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sale::class,
            //'current_user' => null
        ]);
    }
}
