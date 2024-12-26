<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use DateTime;
use DateTimeImmutable;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Producto',
                'label_attr' => ['class' => "form-label"],
                'attr' => ['class' => "form-control"],
                'row_attr' => ['style' => 'position: relative', 'class' => 'col-md-6']
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'id',
                'label' => 'Categoria',
                'label_attr' => ['class' => "form-label"],
                'attr' => ['class' => "form-control"],
                'row_attr' => ['style' => 'position: relative', 'class' => 'col-md-6']
            ])
            ->add('price', NumberType::class, [
                'label' => 'Precio',
                'label_attr' => ['class' => "form-label"],
                'attr' => ['class' => "form-control"],
                'row_attr' => ['style' => 'position: relative', 'class' => 'col-md-6']
            ])
            ->add('stock_quantity', NumberType::class, [
                'label' => 'Existencia',
                'label_attr' => ['class' => "form-label"],
                'attr' => ['class' => "form-control"],
                'row_attr' => ['style' => 'position: relative', 'class' => 'col-md-6']
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Descripción',
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
        $builder->setAttribute('row_attr', ['class' => 'row']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'attr' => ['class' => 'row']
        ]);
    }
}