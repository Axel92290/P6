<?php

namespace App\Form;


use App\Entity\Tricks;
use App\Entity\User;
use App\Form\TricksPhotoType;
use App\Form\TricksVideoType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TricksType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du Trick',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('tricksPhotos', CollectionType::class, [
                'entry_type' => TricksPhotoType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false, // Masque le label par défaut
            ])
            ->add('tricksVideos', CollectionType::class, [
                'entry_type' => TricksVideoType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tricks::class,
        ]);
    }
}
