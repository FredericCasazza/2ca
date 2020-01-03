<?php


namespace App\Form;


use App\Entity\Establishment;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class BecomeClientType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('establishment', EntityType::class, [
            'label' => false,
            'class' => Establishment::class,
            'choice_label' => 'label',
            'query_builder' => function (EntityRepository $er) {
                $qb = $er->createQueryBuilder('e');
                $qb->andWhere($qb->expr()->eq('e.enable', true))
                    ->orderBy('e.label', 'ASC');
                return $qb;
            }
        ])->add('confirm', CheckboxType::class, [
            'label' => "Je souhaite être identifié comme client afin de pouvoir accéder à certaines fonctionnalités de la plateforme comme la réservation de repas en ligne.",
            'label_attr' => ['class' => 'checkbox-custom font-weight-normal']
        ])->add('submit', SubmitType::class, [
            'label' => "Confirmer"
        ]);
    }
}