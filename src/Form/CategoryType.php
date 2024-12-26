<?php

namespace App\Form;

use App\Entity\Category;
use DateTimeImmutable;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Categoria',
                'label_attr' => ['class' => "form-label"],
                'attr' => ['class' => "form-control"],
                'row_attr' => ['style' => 'position: relative', 'class' => 'col-md-12']
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Descripción',
                'label_attr' => ['class' => "form-label"],
                'attr' => ['class' => "form-control"],
                'row_attr' => ['style' => 'position: relative', 'class' => 'col-md-12']
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
            'data_class' => Category::class,
            'attr' => ['class' => 'row']
        ]);
    }
}
