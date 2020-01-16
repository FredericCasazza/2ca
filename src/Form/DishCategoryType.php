<?php


namespace App\Form;


use App\Entity\DishCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DishCategoryType
 * @package App\Form
 */
class DishCategoryType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('label', TextType::class, [
            'label' => "Intitulé"
        ])->add('dishLimit', IntegerType::class, [
            'label' => "Limite de plat autorisé (0 = pas de limite)"
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
            'data_class' => DishCategory::class
        ]);
    }

}