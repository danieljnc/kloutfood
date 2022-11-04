<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Positive;

class ProductType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', EntityType::class, [
                'label'              => 'kf_product_category',
                'translation_domain' => 'kfcore',
                'class'              => Category::class,
            ])
            ->add('name', TextType::class, [
                'label'              => 'kf_product_name',
                'translation_domain' => 'kfcore',
            ])
            ->add('description', TextareaType::class, [
                'label'              => 'kf_product_description',
                'translation_domain' => 'kfcore',
            ])
            ->add('stock', NumberType::class, [
                'label'              => 'kf_product_stock',
                'translation_domain' => 'kfcore',
                'constraints'        => [new Positive()],
            ])
            ->add('leftover', NumberType::class, [
                'label'              => 'kf_product_leftover',
                'translation_domain' => 'kfcore',
                'constraints'        => [new Positive()],
            ])
            ->add('measurementUnit', TextType::class, [
                'label'              => 'kf_product_measurement_unit',
                'translation_domain' => 'kfcore',
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}