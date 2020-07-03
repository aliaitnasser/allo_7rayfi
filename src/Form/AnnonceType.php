<?php

namespace App\Form;

use App\Entity\Annonce;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AnnonceType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfig("Titre :", "Tapez le titre de votre annonce"))
            ->add('introduction', TextareaType::class,$this->getConfig("Description :", "Tapez une déscription de votre annonce"))
            ->add('coverImage', TextType::class, $this->getConfig("Image de couverture :", "Url de votre image de couverture"))
            ->add('price', MoneyType::class, $this->getConfig("Prix :", "Tapez le prix"))
            ->add('images', CollectionType::class, [
                'entry_type' => ImageType::class,
                'allow_add' => true,
                'allow_delete' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}
