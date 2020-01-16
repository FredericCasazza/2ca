<?php


namespace App\Form\Filter;


use App\Entity\Establishment;
use App\Entity\Period;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\DateFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\EntityFilterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class MealFilterType
 * @package App\Form\Filter
 */
class MealFilterType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('date', DateFilterType::class, [
            'label' => 'Date',
            'widget' => 'single_text'
        ])->add('period', EntityFilterType::class, [
            'label' => 'Repas',
            'class' => Period::class,
            'choice_label' => 'label'
        ])->add('establishments', EntityFilterType::class, [
            'label' => 'Etablissement',
            'class' => Establishment::class,
            'choice_label' => 'label'
        ]);
    }

    /**
     * @return string|null
     */
    public function getParent()
    {
        return FilterType::class;
    }
}