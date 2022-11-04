<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Recipe;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/recipe")
 */
class RecipeController extends AbstractCrudController
{
    /**
     * @return string
     */
    function getCurrentObject(): string
    {
        return Recipe::class;
    }

    /**
     * @Route("/", name="kf_recipe_index", methods={"GET"})
     */
    public function indexAction(): Response
    {
        return parent::indexAction();
    }

    /**
     * @Route("/new", name="kf_recipe_new", methods={"GET","POST"})
     */
    public function newAction(Request $request): Response
    {
        return parent::newAction($request);
    }

    /**
     * @Route("/{id}/show", name="kf_recipe_show", methods={"GET"})
     */
    public function showAction(int $id): Response
    {
        return parent::showAction($id);
    }

    /**
     * @Route("/{id}/edit", name="kf_recipe_edit", methods={"GET","PATCH"})
     */
    public function editAction(Request $request, int $id): Response
    {
        return parent::editAction($request,$id);
    }

    /**
     * @Route("/{id}/delete", name="kf_recipe_delete", methods={"DELETE"})
     */
    public function deleteAction(int $id): Response
    {
        return parent::deleteAction($id);
    }

    /**
     * @return \Closure[]
     */
    protected function getCallBacks(): array
    {
        return [
            'beforeFlush' => function (Request $request, Recipe $recipe) {
                if(empty($recipe->getId())) {
                    foreach ($recipe->getProducts() as $recipeProduct) {
                        $product = new Product();
                        $product->setName($recipeProduct->getName());
                        $product->setCategory($recipeProduct->getCategory());
                        $product->setDescription($recipeProduct->getDescription());
                        $product->setStock($recipeProduct->getStock());
                        $product->setLeftover($recipeProduct->getLeftover());

                        $this->em->persist($product);
                    }

                    $this->em->flush();
                }
            }
        ];
    }
}