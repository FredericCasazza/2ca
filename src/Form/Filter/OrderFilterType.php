<?php


namespace App\Form\Filter;


use App\Entity\Establishment;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderExecuterInterface;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\EntityFilterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class OrderFilterType
 * @package App\Form\Filter
 */
class OrderFilterType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('meal', MealFilterType::class, [
            'label' => false,
            'add_shared' => function (FilterBuilderExecuterInterface $qbe) {
               $qbe->addOnce($qbe->getAlias().'.meal', 'm', function (QueryBuilder $filterBuilder, $alias, $joinAlias, Expr $expr) {
                    $filterBuilder->leftJoin($alias . '.meal', $joinAlias);
                });
            }
        ])->add('establishment', EntityFilterType::class, [
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