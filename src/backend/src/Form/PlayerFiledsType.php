<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class PlayerFiledsType extends AbstractType
{
    private $availableFields = ["id", "birthday", "birthPlace", "foot", "role", "height", "number", "country", "team"];

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'choices' => $this->availableFields,
            "invalid_message" => "Available values: `" . implode("`, `", $this->availableFields) . "`",
            "empty_data" => $this->availableFields[0],
        ));
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}