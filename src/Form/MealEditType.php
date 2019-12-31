<?php


namespace App\Form;


use App\Entity\Establishment;
use App\Entity\Meal;
use App\Entity\Period;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            'attr' => [
                'placeholder' => 'Date/heure limite',
                'class' => 'js-datepicker'
            ]
        ]);
    }

    public function getParent()
    {
        return MealType::class;
    }

}