<?php


namespace App\Form;


use App\Entity\Configuration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ParameterType
 * @package App\Form
 */
class ParameterType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('sessionMaxIdleTime', IntegerType::class, [
            'label' => "Déconnexion après temps d'inactivité en secondes",
        ])->add('recaptchaEnable', CheckboxType::class, [
            'required' => false,
            'label' => false
        ])->add('recaptchaSiteKey', TextType::class, [
            'required' => false,
            'label' => 'Clé de site'
        ])->add('recaptchaSecretKey', TextType::class, [
            'required' => false,
            'label' => 'Clé secrète'
        ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Configuration::class
        ]);
    }
}