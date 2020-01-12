<?php


namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class MealEditType
 * @package App\Form
 */
class MealEditType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('bookDateLimit', DateTimeType::class, [
            'label' => 'Date/heure limite de réservation',
            'widget' => 'single_text',
            'html5' => false,
            'attr' => [
                'placeholder' => 'Date/heure limite',
                'class' => 'datetimepicker-input',
                'data-target' => '#bookDateLimitDatetimepicker'
                //'class' => 'js-datepicker'
            ]
        ]);
    }

    public function getParent()
    {
        return MealType::class;
    }

}