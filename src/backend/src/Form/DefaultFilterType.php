<?php

namespace App\Form;

use App\Type\SeekCriteria\SeekCriteria;
use App\Type\SeekCriteria\Types\SeekCriteriaPlayerFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Range;

class DefaultFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateFrom', DateType::class, [
                "widget" => "single_text",
                "empty_data" => $options["dateFrom"],
            ])
            ->add('dateTo', DateType::class, [
                "widget" => "single_text",
                "empty_data" => $options["dateTo"]
            ])
            ->add('orderBy', ChoiceType::class, [
                'choices' => $options["orderByFields"],
                "invalid_message" => "Available values: `" . implode("`, `", $options["orderByFields"]) . "`",
                "empty_data" => $options["orderByFields"][0],
            ])
            ->add('orderDirection', ChoiceType::class, [
                "choices" => SeekCriteria::validOrderDirections,
                "invalid_message" => "Available values: `" . implode("`, `", SeekCriteria::validOrderDirections) . "`",
                "empty_data" => SeekCriteria::validOrderDirections[0]
            ])
            ->add('offset', IntegerType::class, ["empty_data" => "0"])
            ->add('limit', IntegerType::class, [
                "empty_data" => "100",
                "constraints" => [
                    new Range(["max" => 300])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'dateFrom' => "",
            'dateTo' => "",
            'orderByFields' => ["id"],
        ]);
    }    
}