<?php


namespace App\Form;


use App\Entity\Period;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PeriodType
 * @package App\Form
 */
class PeriodType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('label', TextType::class, [
            'label' => 'Intitulé'
        ])->add('startTime', TimeType::class, [
            'label' => 'Heure début service',
        ])->add('bookTimeLimit', IntegerType::class, [
            'label' => "Nombre d'heure(s) avant le début du service définissant la date limite pour réserver"
        ])->add('position', IntegerType::class, [
            'label' => 'Position'
        ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Period::class
        ]);
    }

}