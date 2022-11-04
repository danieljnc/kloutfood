<?php

namespace App\Service;

use App\Entity\Recipe;
use Symfony\Component\HttpFoundation\RequestStack;

class CartManagerService
{
    /**
     * The request stack.
     *
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var array
     */
    private $recipes = [];

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @return array
     */
    public function getCart(): array
    {
        return $this->requestStack->getSession()->get('cart', []);
    }

    /**
     * @param Recipe $recipe
     * @return void
     */
    public function addRecipeToCart(Recipe $recipe): void
    {
        $cart = $this->getCart()[] = $recipe;
        $this->requestStack->getSession()->set('cart', $cart);
    }

    /**
     * @param Recipe $recipe
     * @return void
     */
    public function removeRecipeFromCart(Recipe $recipe): void
    {
        $cart = array_filter($this->getCart(), function ($cartRecipe) use ($recipe) {
            return $cartRecipe->getId() !== $recipe->getId();
        });

        $this->requestStack->getSession()->set('cart', $cart);
    }
}