<?php

namespace App\EventListener;

use App\Entity\ProductRecipe;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class ProductRecipeListener
{
    /**
     * @param ProductRecipe      $productRecipe
     * @param LifecycleEventArgs $args
     * @return void
     */
    public function prePersist(ProductRecipe $productRecipe, LifecycleEventArgs $args): void
    {
        $entityManager = $args->getObjectManager();
        $product = $productRecipe->getProduct();
        if ($product) {
            $leftOver = $product->getLeftover() - $productRecipe->getQuantity();
            $product->setLeftover($leftOver < 0 ? 0 : $leftOver);

            if ($leftOver < 0) {
                $product->setStock($product->getStock() + $leftOver);
            }

            $entityManager->persist($product);
            $entityManager->flush();
        }
    }
}