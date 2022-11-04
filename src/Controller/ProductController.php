<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product")
 */
class ProductController extends AbstractCrudController
{
    /**
     * @return string
     */
    function getCurrentObject(): string
    {
        return Product::class;
    }

    /**
     * @Route("/", name="kf_product_index", methods={"GET"})
     */
    public function indexAction(): Response
    {
        return parent::indexAction();
    }

    /**
     * @Route("/new", name="kf_product_new", methods={"GET","POST"})
     */
    public function newAction(Request $request): Response
    {
        return parent::newAction($request);
    }

    /**
     * @Route("/{id}/show", name="kf_product_show", methods={"GET"})
     */
    public function showAction(int $id): Response
    {
        return parent::showAction($id);
    }

    /**
     * @Route("/{id}/edit", name="kf_product_edit", methods={"GET","PATCH"})
     */
    public function editAction(Request $request, int $id): Response
    {
        return parent::editAction($request,$id);
    }

    /**
     * @Route("/{id}/delete", name="kf_product_delete", methods={"DELETE"})
     */
    public function deleteAction(int $id): Response
    {
        return parent::deleteAction($id);
    }
}