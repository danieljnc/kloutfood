<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\ProductRecipe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Positive;

class ProductRecipeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product', EntityType::class, [
                'label'              => 'kf_recipe_product',
                'translation_domain' => 'kfcore',
                'class'              => Product::class,
            ])
            ->add('quantity', NumberType::class, [
                'label'              => 'kf_recipe_product_quantity',
                'translation_domain' => 'kfcore',
                'constraints'        => [new Positive()],
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductRecipe::class,
        ]);
    }
}