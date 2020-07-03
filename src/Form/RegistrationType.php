<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RegistrationType extends ApplicationType
{
    const JOB = ['Plomberie', 'Ma�onnerie', 'Climatisation', 'Chauffage'];
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, $this->getConfig("Pr�nom", "Votre pr�nom..."))
            ->add('lastName', TextType::class, $this->getConfig("Nom", "Votre nom..."))
            ->add('email', EmailType::class, $this->getConfig("Email", "Votre adresse Email..."))
            ->add('age', IntegerType::class, $this->getConfig("�ge", "Votre �ge..."))
            ->add('phone', TextType::class, $this->getConfig("T�l�phone", "Votre t�l�phone..."))
            ->add('address', TextType::class, $this->getConfig("Adresse", "Votre adresse..."))
            ->add('city', TextType::class, $this->getConfig("Ville", "Votre ville..."))
            ->add('job', ChoiceType::class,[
                'choices' => array_combine(self::JOB, self::JOB)
            ], $this->getConfig("M�tier", "Selectionner votre m�tier"))
            ->add('picture', UrlType::class, $this->getConfig("Photo de profile", "Url de votre photo..."))
            ->add('hash', PasswordType::class, $this->getConfig("Mot de passe", "Votre mot de passe..."))
            ->add('passwordConfirm', PasswordType::class, $this->getConfig("Confirmation de mot de passe", "Veuillez confirmer votre mot de passe..."))
            ->add('introduction', TextareaType::class, $this->getConfig("Pr�entation", "Pr�sentez vous..."))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
