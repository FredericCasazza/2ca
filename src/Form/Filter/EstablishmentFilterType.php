<?php


namespace App\Form\Filter;


use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\TextFilterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class EstablishmentFilterType
 * @package App\Form\Filter
 */
class EstablishmentFilterType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('label', TextFilterType::class, [
            'label' => 'Etablissement'
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