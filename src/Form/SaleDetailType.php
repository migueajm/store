<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Sale;
use App\Entity\SaleDetail;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SaleDetailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class, [
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'form-control'],
                'label' => "Codigo de barras",
                'label_attr' => ['class' => 'form-label']
            ])
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'choice_label' => function ($product) {
                    return $product->getName() . ' (' . $product->getPrice() . ')';
                },
                'choice_attr' => function ($product) {
                    return [
                        'data-price' => $product->getPrice(),
                        'data-code' => $product->getCode()
                    ];
                },
                'label' => 'Producto',
                'label_attr' => ['class' => "form-label"],
                'attr' => ['class' => "form-control"],
                'row_attr' => ['style' => 'position: relative', 'class' => 'col-md-12'],
                'placeholder' => 'Seleccione una opción',
            ])
            ->add('unit_price', NumberType::class, ['row_attr' => ['hidden' => true]])
            ->add('quantity', NumberType::class, [
                'label' => 'Cantidad',
                'label_attr' => ['class' => "form-label"],
                'attr' => ['class' => "form-control"],
                'row_attr' => ['style' => 'position: relative', 'class' => 'col-md-12'],
                'data' => 1
            ])
            ->add('total_price', NumberType::class, [
                'label' => 'Total',
                'label_attr' => ['class' => "form-label"],
                'attr' => ['class' => "form-control"],
                'row_attr' => ['style' => 'position: relative', 'class' => 'col-md-12'],
                'data' => 0.0
            ])
            ->add('sale', EntityType::class, [
                'class' => Sale::class,
                'choice_label' => 'id',
                'row_attr' => ['hidden' => true]
            ])
            ->add('next', SubmitType::class, [
                'label' => "Siguiente",
                'attr' => ['class' => "btn btn-outline-success"],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SaleDetail::class,
            'attr' => ['class' => 'row']
        ]);
    }
}
