<?php

namespace App\Controller;

use App\Traits\FormErrorMessagesTrait;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractCrudController extends AbstractController
{
    use FormErrorMessagesTrait;

    /**
     * @var array|null
     */
    protected $objectAsArray = null;

    /**
     * @var TranslatorInterface
     */
    protected $translator;
    /**
     * @var EntityManagerInterface
     */
    protected $em;
    /**
     * @var SessionInterface
     */
    protected $session;

    public function __construct(
        TranslatorInterface $translator,
        EntityManagerInterface $em,
        SessionInterface $session
    ) {
        $this->translator = $translator;
        $this->em = $em;
        $this->session = $session;
    }

    /**
     * @return Response
     */
    public function indexAction(): Response
    {
        return $this->render($this->getIndexRender(), ['data' => $this->getListData()]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function newAction(Request $request): Response
    {
        $currentObject = $this->getCurrentObject();
        $object = new $currentObject();
        $url = $this->generateUrl($this->getNewUrl(), $this->additionalRoutesParams($request));
        $form = $this->getForm($object, $url);
        $result = $this->handleForm($request, $object, $form, $this->getCallBacks());

        if (!$result) {
            return $this->render($this->getEditRender(), [
                'entity' => $object,
                'isNew'  => true,
                'form'   => $form->createView(),
            ]);
        }

        return $result;
    }

    /**
     * @param Request $request
     * @param int     $id
     * @return Response
     */
    public function editAction(Request $request, int $id): Response
    {
        $entity = $this->getRepository()->find($id);
        $formParams = array_merge(['id' => $id, $this->additionalRoutesParams($request)]);
        $url = $this->generateUrl($this->getEditUrl(), $formParams);
        $editForm = $this->getForm($entity, $url);
        $result = $this->handleForm($request, $entity, $editForm, $this->getCallBacks());

        if (!$result) {
            return $this->render($this->getEditRender(), [
                'entity' => $entity,
                'isNew' => false,
                'form' => $editForm->createView()]
            );
        }

        return $result;
    }

    /**
     * @param int $id
     * @return Response
     */
    public function showAction(int $id): Response
    {
        return $this->render($this->getShowRender(), ['entity' => $this->getRepository()->find($id)]);
    }

    /**
     * @param $id
     * @return Response
     */
    public function deleteAction($id): Response
    {
        $this->delete($this->getRepository()->find($id));
        return $this->redirectToRoute($this->getListUrl());
    }

    /**
     * @param object $object
     * @return bool
     */
    protected function delete(object $object): bool
    {
        if ($this->canBeDeleted($object)) {
            try {
                $this->em->remove($object);
                $this->em->flush();

                return true;
            } catch (\Exception $e) {

                return false;
            }
        }

        return false;
    }

    /**
     * @return array
     */
    public function getUrlRoutes(): array
    {
        return [
            'new'    => $this->getNewUrl(),
            'edit'   => $this->getEditUrl(),
            'delete' => $this->getDeleteUrl(),
            'view'   => $this->getListUrl(),
        ];
    }

    /**
     * @return string
     */
    public function getSingleTableName(): string
    {
        return strtolower($this->getObjectAsArray()[1]);
    }

    /**
     * @return string
     */
    abstract function getCurrentObject(): string;

    /**
     * @return array
     */
    protected function additionalRenderParams(): array
    {
        return [
            'url'       => $this->getUrlRoutes(),
            'tb_name'   => $this->getSingleTableName(),
            'tb_domain' => $this->getTranslateDomain(),
        ];
    }

    /**
     * @return string
     */
    protected function getTranslateDomain(): string
    {
        return $this->getSingleTableName();
    }

    /**
     * @return string
     */
    protected function getNewUrl(): string
    {
        return $this->getObjectAsRouteUrl().'_new';
    }

    /**
     * @return string
     */
    protected function getEditUrl(): string
    {
        return $this->getObjectAsRouteUrl().'_edit';
    }

    /**
     * @return string
     */
    protected function getDeleteUrl(): string
    {
        return $this->getObjectAsRouteUrl().'_delete';
    }

    /**
     * @return string
     */
    protected function getListUrl(): string
    {
        return $this->getObjectAsRouteUrl().'_list';
    }

    /**
     * @return array|null
     */
    protected function getObjectAsArray(): array
    {
        if ($this->objectAsArray == null) {
            $flag = str_replace('Bundle', '', $this->getCurrentObject());
            $flag = explode('\\', $flag);
            $this->objectAsArray = $flag;
        }

        return $this->objectAsArray;
    }

    /**
     * @return string
     */
    protected function getObjectAsRouteUrl(): string
    {
        $flag = str_replace('Kf', 'kf_', $this->getObjectAsArray()[3]);

        return strtolower($flag);
    }

    /**
     * @return ObjectRepository
     */
    protected function getRepository(): ObjectRepository
    {
        return $this->em->getRepository($this->getCurrentObject());
    }

    /**
     * @param $type
     * @return string
     */
    protected function getObjectAsRouteTwig($type): string
    {
        $flag = $this->getObjectAsArray();
        $ret = $flag[0].$flag[1].'/'.$flag[3].'/'.$type.'.html.twig';

        unset($flag);

        return $ret;
    }

    /**
     * @return string
     */
    protected function getIndexRender(): string
    {
        return $this->getObjectAsRouteTwig('index');
    }

    /**
     * @return string
     */
    protected function getEditRender(): string
    {
        return $this->getObjectAsRouteTwig('edit');
    }

    /**
     * @return string
     */
    protected function getShowRender(): string
    {
        return $this->getObjectAsRouteTwig('show');
    }

    /**
     * @return array
     */
    protected function getListData(): array
    {
        return $this->getRepository()->findAll();
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function additionalRoutesParams(Request $request): array
    {
        return [];
    }

    /**
     * @param object $entity
     * @param string $url
     * @return FormInterface
     */
    protected function getForm(object $entity, string $url): FormInterface
    {
        $form = $this->createForm($this->getFormType(), $entity,
            array_merge([
                'method' => $this->getMethod(),
                'action' => $url,
            ],
                $this->getFormAdditionalOptions()
            )
        );

        $form->add('submit', SubmitType::class, [
            'label'              => 'kf_button_save_label',
            'translation_domain' => 'kfcore',
            'attr'               => ['class' => 'btn btn-primary'],
        ]);

        return $form;
    }

    /**
     * @return array
     */
    protected function getFormAdditionalOptions(): array
    {
        return [];
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return 'POST';
    }

    /**
     * @return string
     */
    protected function getFormType(): string
    {
        return str_replace('Entity', 'Form', $this->getCurrentObject()).'Type';
    }

    /**
     * @param Request $request
     * @param         $object
     * @param Form    $form
     * @param array   $callback
     * @return false|JsonResponse|RedirectResponse
     */
    protected function handleForm(Request $request, $object, Form $form, array $callback = [])
    {
        $isAjax = $request->isXmlHttpRequest();
        $json = ['status' => 1, 'error' => [], 'errorMessage' => ''];

        $this->handleCallbackHandleForm($callback, 'beforeHandleForm', $request, $object);
        $form->handleRequest($request);
        $this->handleCallbackHandleForm($callback, 'afterHandleForm', $request, $object);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($object);

            $this->handleCallbackHandleForm($callback, 'beforeFlush', $request, $object);
            $this->em->flush();
            $this->handleCallbackHandleForm($callback, 'afterFlush', $request, $object);

            if ($isAjax) {
                return new JsonResponse($json);
            } else {
                $this->get('session')->getFlashBag()->add(
                    'alert alert-success',
                    $this->translator->trans('kf_data_saved_successfully', [], 'kfcore')
                );

                return $this->redirectToRoute($this->getListUrl());
            }

        } elseif ($form->isSubmitted()) {
            if ($isAjax) {
                $json['status'] = 0;
                $json['error'] = $this->getFormErrorMessages($form);

                return new JsonResponse($json);
            } else {
                $this->session->getFlashBag()->add(
                    'alert alert-danger',
                    $this->translator->trans('kf_data_unsaved', [], 'kfcore')
                );
            }
        }

        return false;
    }

    /**
     * @param array   $callbacks
     * @param string  $event
     * @param Request $request
     * @param         $object
     * @return void
     */
    protected function handleCallbackHandleForm(array $callbacks, string $event, Request $request, $object): void
    {
        if (array_key_exists($event, $callbacks) and is_callable($callbacks[$event])) {
            $callbacks[$event]($request, $object);
        }
    }

    /**
     * @return array
     */
    protected function getCallBacks(): array
    {
        return [];
    }

    /**
     * @param object $object
     * @return bool
     */
    protected function canBeDeleted(object $object): bool
    {
        return true;
    }
}