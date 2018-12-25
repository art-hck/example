<?php

namespace App\Form;

use App\Form\Extension\Core\Type\SeekCriteriaRangeType;
use App\Type\SeekCriteria\Types\SeekCriteriaTransferFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransferFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fee', SeekCriteriaRangeType::class)
            ->add('mv', SeekCriteriaRangeType::class)
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
            'orderByFields' => SeekCriteriaTransferFilter::getOrderByFields(),
        ]);
    }
}