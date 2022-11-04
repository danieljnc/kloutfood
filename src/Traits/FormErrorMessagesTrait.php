<?php

namespace App\Traits;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;

trait FormErrorMessagesTrait
{
    /**
     * @param Form $form
     * @return array
     */
    public function getFormErrorMessages(Form $form): array
    {

        $errors = array('formName' => $form->getName(), 'messages' => array());

        foreach ($form->getErrors(true) as $error) {
            $errors['messages'][] = array(
                'message' => $error->getMessage(),
                'field' => implode('_', $this->generatePathField($error))
            );
        }

        return $errors;
    }

    /**
     * @param FormError $error
     * @return array
     */
    private function generatePathField(FormError $error): array
    {
        $path = [$error->getOrigin()->getName()];
        $parent = $error->getOrigin()->getParent();
        while ($parent) {
            $path[] = $parent->getName();
            $parent = $parent->getParent();
        }

        return array_reverse($path);
    }
}