<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;

class PlayersFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateFrom', DateType::class, ["widget" => "single_text"])
            ->add('dateTo', DateType::class, ["widget" => "single_text"])

            ->add('leagueId', IntegerType::class)

            ->add('teamId', IntegerType::class)

            ->add('minGoals', IntegerType::class)
            ->add('maxGoals', IntegerType::class)

            ->add('minTime', IntegerType::class) // TODO!
            ->add('maxTime', IntegerType::class)

            ->add('minCards', IntegerType::class)
            ->add('maxCards', IntegerType::class)
            ->add('cardsType', IntegerType::class)

            ->add('minPlayTime', IntegerType::class)
            ->add('maxPlayTime', IntegerType::class)

            ->add('orderBy', PlayerFiledsType::class, ["empty_data" => "id"])
            ->add('orderDirection', ChoiceType::class, [
                "choices" => ["ASC", "DESC"],
                "invalid_message" => "Available values: `ASC`, `DESC`",
                "empty_data" => "ASC"
            ])
            ->add('limit', IntegerType::class, ["empty_data" => "100"])
            ->add('offset', IntegerType::class, ["empty_data" => "0"])
        ;
    }
}