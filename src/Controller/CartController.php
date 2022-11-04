<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Service\CartManagerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cart")
 */
class CartController extends AbstractController
{
    /**
     * @Route("/", name="kf_cart")
     */
    public function index(CartManagerService $cartManagerService): Response
    {
        $cart = $cartManagerService->getCart();

        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
        ]);
    }

    /**
     * @Route("/add/{id}", name="kf_cart_add")
     */
    public function add(Recipe $recipe, CartManagerService $cartManagerService): RedirectResponse
    {
        $cartManagerService->addRecipeToCart($recipe);

        return $this->redirectToRoute('kf_cart');
    }

    /**
     * @Route("/remove/{id}", name="kf_cart_remove")
     */
    public function remove(Recipe $recipe, CartManagerService $cartManagerService): RedirectResponse
    {
        $cartManagerService->removeRecipeFromCart($recipe);

        return $this->redirectToRoute('kf_cart');
    }
}