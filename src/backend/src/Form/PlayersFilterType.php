<?php

namespace App\Form;

use App\DBAL\Types\PlayerRoleType;
use App\Form\Extension\Core\Type\SeekCriteriaRangeType;
use App\Type\PlayerRole\PlayerRoleFactory;
use App\Type\SeekCriteria\SeekCriteria;
use App\Type\SeekCriteria\Types\SeekCriteriaPlayerFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Range;

class PlayersFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateFrom', DateType::class, ["widget" => "single_text"])
            ->add('dateTo', DateType::class, ["widget" => "single_text"])

            ->add('playerName', TextType::class)
            ->add('leagueId', IntegerType::class)
            
            ->add('teamId', IntegerType::class)
            ->add('teamName', TextType::class)
            ->add('leagueName', TextType::class)

            ->add("role", ChoiceType::class, [
                "choices" => array_map(
                    function($roleName) {
                        return PlayerRoleFactory::createFromString($roleName);
                    }, PlayerRoleType::getChoices()
                ),
                "empty_data" => null,
                // будет использоваться метод getStringCode
                // (см. http://symfony.com/doc/current/reference/forms/types/choice.html#choice-label)
                "choice_value" => "name",
                "required" => false,
            ])

            ->add('international', CheckboxType::class)
            ->add('assists', SeekCriteriaRangeType::class)
            ->add('goals', SeekCriteriaRangeType::class)
            ->add('age', SeekCriteriaRangeType::class)
            ->add('playTime', SeekCriteriaRangeType::class)
            ->add('cards', SeekCriteriaRangeType::class)
            ->add('cardsType', IntegerType::class)
            ->add('height', SeekCriteriaRangeType::class)

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
            ->add(
                'limit', 
                IntegerType::class, 
                [
                    "empty_data" => "100",
                    "constraints"=>[
                        new Range(["max" => 300])
                    ]
                ]
            )
        ;
    }
}