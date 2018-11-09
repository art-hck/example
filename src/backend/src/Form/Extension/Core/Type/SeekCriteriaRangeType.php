<?php

namespace App\Form\Extension\Core\Type;

use App\Type\SeekCriteria\SeekCriteriaException;
use App\Type\SeekCriteria\SeekCriteriaRange;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class SeekCriteriaRangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addModelTransformer(new CallbackTransformer(
                function (?SeekCriteriaRange $range) {
                    if($range) {
                        return json_encode([$range->min, $range->max]);
                    } else {
                        return null;
                    }
                },
                function (?string $string) {
                    try {
                        list($min, $max) = array_pad(json_decode($string) ?? [], 2, null);
                        $seekCriteriaRange = new SeekCriteriaRange($min, $max);
                    } catch (SeekCriteriaException $e) {
                        return null;
                    }

                    return $seekCriteriaRange;
                }
            ))
        ;        
    }

    public function getParent()
    {
        return TextType::class;
    }    
}