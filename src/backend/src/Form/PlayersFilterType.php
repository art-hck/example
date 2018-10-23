<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
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
            ->add('minTime', IntegerType::class)
            ->add('maxTime', IntegerType::class)
        ;
    }
}