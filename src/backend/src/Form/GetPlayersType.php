<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;


class GetPlayersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('field', PlayerFiledsType::class)
            ->add('value', TextType::class, ["constraints" => [new NotBlank()]])
            ->add("orderBy", PlayerFiledsType::class)
            ->add('orderDirection', ChoiceType::class, [
                "choices" => ["ASC", "DESC"],
                "invalid_message" => "Available values: `ASC`, `DESC`",
                "empty_data" => "ASC"
            ])
            ->add('limit', IntegerType::class, ["empty_data" => 20]) // @TODO: always invalid if not defined!
            ->add('offset', IntegerType::class, ["empty_data" => 0]) // @TODO: always invalid if not defined!
        ;
    }
}