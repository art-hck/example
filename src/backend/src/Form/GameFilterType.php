<?php

namespace App\Form;

use App\Form\Extension\Core\Type\SeekCriteriaRangeType;
use App\Type\SeekCriteria\Types\SeekCriteriaGameFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameFilterType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('teamId', IntegerType::class)
            ->add('duration', SeekCriteriaRangeType::class)
        ;
    }
    
    public function getParent()
    {
        return DefaultFilterType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'dateFrom' => (new \DateTime())->setTimestamp(strtotime("previous year 1 August"))->format(DATE_ISO8601),
            'dateTo' => (new \DateTime())->setTimestamp(strtotime("this year 15 December"))->format(DATE_ISO8601),
            'orderByFields' => SeekCriteriaGameFilter::getOrderByFields(),
        ]);
    }
}