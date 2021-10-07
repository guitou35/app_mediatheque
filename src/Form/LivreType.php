<?php

namespace App\Form;

use App\Entity\Auteur;
use App\Entity\Genre;
use App\Entity\Livre;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LivreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('dateParution')
            ->add('imageFile',FileType::class,[
                'required'=> false
            ])
            ->add('description', TextareaType::class)
            ->add('statut', ChoiceType::class,[
                'choices'=>[
                    'disponible'=>  'dispo',
                    'reservé'=> 'reservé',
                    'non disponible'=> 'nodispo'
                ]
            ])
            ->add('genre', EntityType::class,[
                'class' => Genre::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('g')
                        ->orderBy('g.nom', 'ASC');
                },
                'choice_label' => 'nom',
                'choice_value'=> 'id'
            ])
            ->add('auteur', EntityType::class,[
                'class' => Auteur::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.nom', 'ASC');
                },
                'choice_label' => 'nom',
                'choice_value'=> 'id'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livre::class,
        ]);
    }
}
