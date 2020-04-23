<?php

namespace App\Form;

use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectStatusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('statut', ChoiceType::class, [
            'choices'  => [
                'Nouveau' => 'nouveau',
                'En Cours' => 'en cours',
                'Terminé' => 'terminé',
            ]
        ])
            ->add('Changer', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }
}
