<?php

namespace App\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SearchType extends ApplicationType
{
    const CITIES = ['agadir','assilah', 'azrou','azilal','al hoceima','bouznika','berkan','boujdour','casablanca','chefchaouen','dakhla','essaouira','fes','fnideq','ifrane','jerada','kénitra','khémisset','khourbibga','laayoune','marrakesh','meknes','mohammédia','nadour','oujeda','ouarzazat','rabat','salé','safi','settat','tanger','témara','taza','tétouan','zagora'];
    const JOB = ['balayeur','chaufage','charpenterie','electricité','fleuriste','menuisier','ouvrier du batiment','peintres','pompes a chaleur','plamberie','serrurerie','soudure','tapissier'];
    
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
