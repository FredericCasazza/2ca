<?php


namespace App\Form;


use App\Entity\Establishment;
use App\Entity\Meal;
use App\Entity\Period;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class MealType
 * @package App\Form
 */
class MealType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('date', DateType::class, [
            'label' => false,
            'widget' => 'single_text',
            'attr' => [
                'placeholder' => 'Date',
                'class' => 'js-datepicker'
            ]
        ])->add('period', EntityType::class, [
            'label' => false,
            'class' => Period::class,
            'choice_label' => 'label',
            'query_builder' => function (EntityRepository $er) {
                $qb = $er->createQueryBuilder('p');
                $qb->andWhere($qb->expr()->eq('p.enable', true))
                    ->orderBy('p.label', 'ASC');
                return $qb;
            },
            'attr' => [
                'placeholder' => 'Repas'
            ]
        ])->add('establishments', EntityType::class, [
                'label' => 'Etablissements',
                'multiple' => true,
                'expanded' => true,
                'class' => Establishment::class,
                'choice_label' => 'label',
                'query_builder' => function (EntityRepository $er) {
                    $qb = $er->createQueryBuilder('e');
                    $qb->andWhere($qb->expr()->eq('e.enable', true))
                        ->orderBy('e.label', 'ASC');
                    return $qb;
                },
                'label_attr' => [
                    'class' => 'checkbox-custom'
                ],
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Meal::class
        ]);
    }

}