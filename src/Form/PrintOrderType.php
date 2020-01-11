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

/**
 * Class PrintOrderType
 * @package App\Form
 */
class PrintOrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('date', DateType::class, [
            'label' => 'Date',
            'widget' => 'single_text',
            'html5' => true
        ])->add('period', EntityType::class, [
            'label' => 'Repas',
            'class' => Period::class,
            'choice_label' => 'label',
            'query_builder' => function(EntityRepository $entityRepository)
            {
                return $entityRepository->createQueryBuilder('p')
                    ->addOrderBy('p.position', 'ASC');
            }
        ])->add('establishment', EntityType::class, [
            'label' => 'Etablissement',
            'class' => Establishment::class,
            'choice_label' => 'label',
            'query_builder' => function(EntityRepository $entityRepository)
            {
                return $entityRepository->createQueryBuilder('e')
                    ->addOrderBy('e.label', 'ASC');
            }
        ]);
    }
}