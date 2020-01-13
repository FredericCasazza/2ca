<?php


namespace App\Form;


use App\Entity\Configuration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
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
        ])->add('mailerHost', TextType::class, [
            'required' => false,
            'label' => 'Host'
        ])->add('mailerPort', IntegerType::class, [
            'required' => false,
            'label' => 'Port'
        ])->add('mailerEncryption', TextType::class, [
            'required' => false,
            'label' => 'Encryption'
        ])->add('mailerAuthMode', TextType::class, [
            'required' => false,
            'label' => 'Authentication mode'
        ])->add('mailerUsername', TextType::class, [
            'required' => false,
            'label' => 'Username'
        ])->add('mailerPassword', PasswordType::class, [
            'required' => false,
            'label' => 'Password'
        ])->add('mailerTimeout', TextType::class, [
            'required' => false,
            'label' => 'Timeout'
        ])->add('mailerSubjectPrefix', TextType::class, [
            'required' => false,
            'label' => 'Subject prefix'
        ])->add('mailerFrom', EmailType::class, [
            'required' => false,
            'label' => 'Auto from'
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