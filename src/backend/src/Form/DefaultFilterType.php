<?php

namespace App\Form;

use App\Type\SeekCriteria\SeekCriteria;
use App\Type\SeekCriteria\Types\SeekCriteriaPlayerFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Range;

class DefaultFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateFrom', DateType::class, [
                "widget" => "single_text",
                'empty_data' => (new \DateTime())->setTimestamp(strtotime("previous year 1 August"))->format(DATE_ISO8601),
            ])
            ->add('dateTo', DateType::class, [
                "widget" => "single_text",
                'empty_data' => (new \DateTime())->setTimestamp(strtotime("this year 1 May"))->format(DATE_ISO8601),
            ])
            ->add('orderBy', ChoiceType::class, [
                'choices' => SeekCriteriaPlayerFilter::getOrderByFields(),
                "invalid_message" => "Available values: `" . implode("`, `", SeekCriteriaPlayerFilter::getOrderByFields()) . "`",
                "empty_data" => SeekCriteriaPlayerFilter::getOrderByFields()[0],
            ])
            ->add('orderDirection', ChoiceType::class, [
                "choices" => SeekCriteria::validOrderDirections,
                "invalid_message" => "Available values: `" . implode("`, `", SeekCriteria::validOrderDirections) . "`",
                "empty_data" => SeekCriteria::validOrderDirections[0]
            ])
            ->add('offset', IntegerType::class, ["empty_data" => "0"])
            ->add('limit', IntegerType::class, [
                "empty_data" => "100",
                "constraints"=>[
                    new Range(["max" => 300])
                ]
            ])
        ;
    }
}