<?php


namespace App\Form;


use App\Entity\Dish;
use App\Entity\DishCategory;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class MealType
 * @package App\Form
 */
class DishType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('category', EntityType::class, [
            'label' => false,
            'class' => DishCategory::class,
            'choice_label' => 'label',
            'query_builder' => function (EntityRepository $er) {
                $qb = $er->createQueryBuilder('dc');
                $qb->andWhere($qb->expr()->eq('dc.enable', true))
                    ->orderBy('dc.position', 'ASC');
                return $qb;
            },
            'attr' => [
                'placeholder' => 'Repas'
            ]
        ])->add('label', TextType::class, [
            'label' => false,
            'attr' => [
                'placeholder' => 'Intitulé',
            ]
        ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Dish::class
        ]);
    }

}