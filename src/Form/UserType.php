<?php


namespace App\Form;


use App\Constant\Role;
use App\Entity\Establishment;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Doctrine\ORM\QueryBuilder;

/**
 * Class UserType
 * @package App\Form
 */
class UserType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('lastname', TextType::class, [
            'label' => 'Nom'
        ])->add('firstname', TextType::class, [
            'label' => 'Prénom'
        ])->add('email', EmailType::class, [
            'label' => 'Email'
        ])->add('establishment', EntityType::class, [
            'label' => 'Etablissement',
            'required' => false,
            'class' => Establishment::class,
            'choice_label' => 'label',
            'query_builder' => function (EntityRepository $er) {
                $qb = $er->createQueryBuilder('e');
                $qb->andWhere($qb->expr()->eq('e.enable', true))
                    ->orderBy('e.label', 'ASC');
                return $qb;
            }
        ])->add('roles', ChoiceType::class, [
            'label' => false,
            'expanded' => true,
            'multiple' => true,
            'choices' => array_flip(Role::ROLES)
        ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }
}