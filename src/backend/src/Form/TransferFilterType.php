<?php

namespace App\Form;

use App\Form\Extension\Core\Type\SeekCriteriaRangeType;
use App\Type\SeekCriteria\Types\SeekCriteriaTransferFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class TransferFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fee', SeekCriteriaRangeType::class)
            ->add('mv', SeekCriteriaRangeType::class)
            ->add('orderBy', ChoiceType::class, [
                'choices' => SeekCriteriaTransferFilter::getOrderByFields(),
                "invalid_message" => "Available values: `" . implode("`, `", SeekCriteriaTransferFilter::getOrderByFields()) . "`",
                "empty_data" => SeekCriteriaTransferFilter::getOrderByFields()[0],
            ])
        ;
    }

    public function getParent()
    {
        return DefaultFilterType::class;
    }
}