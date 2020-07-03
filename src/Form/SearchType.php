<?php

namespace App\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SearchType extends ApplicationType
{
    const CITIES = ['rabat', 'casablanca', 'sale', 'kenitra', 'fes', 'marrakech', 'tanger', 'asilah', 'tetouan', 'agadir', 'laayoun', 'oujda', 'elhoceima'];
    const JOB = ['plomberie', 'maconnerie', 'climatisation', 'chauffage', 'chauffeur','coiffeur', 'peinture', 'femme de ménage', 'soudeur'];
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('city', ChoiceType::class, [
                'choices' => array_combine(self::CITIES, self::CITIES),
                'label' => 'Villes',
                'attr' => array('class' => 'text-capitalize')
            ])
            ->add('job', ChoiceType::class,[
                'choices' => array_combine(self::JOB, self::JOB),
                'label' => 'Métiers',
                'attr' => array('class' => 'text-capitalize')
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
