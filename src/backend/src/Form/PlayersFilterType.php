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
            ->add('age', SeekCriteriaRangeType::class)
            ->add('assists', SeekCriteriaRangeType::class)
            ->add('cards', SeekCriteriaRangeType::class)
            ->add('cardsType', IntegerType::class)
            ->add('goals', SeekCriteriaRangeType::class)
            ->add('height', SeekCriteriaRangeType::class)
            ->add('international', CheckboxType::class)
            ->add('leagueId', IntegerType::class)
            ->add('leagueName', TextType::class)
            ->add('playerName', TextType::class)
            ->add('playTime', SeekCriteriaRangeType::class)
            ->add("role", ChoiceType::class, [
                "choices" => array_map(function($roleName) {
                    return PlayerRoleFactory::createFromString($roleName);
                }, PlayerRoleType::getChoices()),
                "empty_data" => null,
                "choice_value" => "name", // будет использоваться геттер getName()
                "required" => false,
            ])
            ->add('teamId', IntegerType::class)
            ->add('teamName', TextType::class)

            ->add('orderBy', ChoiceType::class, [
                'choices' => SeekCriteriaPlayerFilter::getOrderByFields(),
                "invalid_message" => "Available values: `" . implode("`, `", SeekCriteriaPlayerFilter::getOrderByFields()) . "`",
                "empty_data" => SeekCriteriaPlayerFilter::getOrderByFields()[0],
            ])
        ;
    }

    public function getParent()
    {
        return DefaultFilterType::class;
    }    
}