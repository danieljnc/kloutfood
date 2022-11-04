<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category")
 */
class CategoryController extends AbstractCrudController
{
    /**
     * @return string
     */
    function getCurrentObject(): string
    {
        return Category::class;
    }

    /**
     * @Route("/", name="kf_category_index", methods={"GET"})
     */
    public function indexAction(): Response
    {
        return parent::indexAction();
    }

    /**
     * @Route("/new", name="kf_category_new", methods={"GET","POST"})
     */
    public function newAction(Request $request): Response
    {
        return parent::newAction($request);
    }

    /**
     * @Route("/{id}/show", name="kf_category_show", methods={"GET"})
     */
    public function showAction(int $id): Response
    {
        return parent::showAction($id);
    }

    /**
     * @Route("/{id}/edit", name="kf_category_edit", methods={"GET","PATCH"})
     */
    public function editAction(Request $request, int $id): Response
    {
        return parent::editAction($request,$id);
    }

    /**
     * @Route("/{id}/delete", name="kf_category_delete", methods={"DELETE"})
     */
    public function deleteAction(int $id): Response
    {
        return parent::deleteAction($id);
    }
}