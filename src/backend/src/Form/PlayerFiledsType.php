<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class PlayerFiledsType extends AbstractType
{
    protected const availableFields = ["id", "birthday", "birthPlace", "foot", "role", "height", "number", "country", "team", "goals", "cards", "playTime"];

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'choices' => self::availableFields,
            "invalid_message" => "Available values: `" . implode("`, `", self::availableFields) . "`",
            "empty_data" => self::availableFields[0],
        ]);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}