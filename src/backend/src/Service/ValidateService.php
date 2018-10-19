<?php

namespace App\Service;

use App\Exception\BadRestRequestHttpException;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class ValidateService
{
    private $formFactory;

    function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function validate(Request $request, string $type, $inputData = null, $outputData = null): FormInterface
    {
        $form = $this->formFactory->createNamed(null, $type, $outputData);

        $form->handleRequest($request);

        if (!$form->isSubmitted()) {

            $body = $inputData ?? json_decode($request->getContent(), true);

            $clearMissing = $request->getMethod() != 'PATCH';
            $form->submit($body, $clearMissing);
        }

        $this->handleErrors($form);

        return $form;
    }

    public function handleErrors(FormInterface $form): FormInterface
    {
        if (!$form->isValid()) {
            $errors = [];

            /** @var FormInterface $input */
            foreach (array_merge($form->all(), [$form]) as $input) {
                foreach ($input->getErrors() as $error) {
                    $errors[$input->getName()] = $error->getMessage();
                }
            }

            throw new BadRestRequestHttpException($errors);
        }
        return $form;
    }
}