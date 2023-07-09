<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Currency;
use App\Entity\User;
use App\Entity\Wallet;
use App\Entity\WalletType AS WalletTypeClass;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WalletType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', EntityType::class, [
                'class' => User::class,
            ])
            ->add('name', TextType::class)
            ->add('balance', TextType::class)
            ->add('currency', EntityType::class, [
                'class' => Currency::class
            ])
            ->add('type', EntityType::class, [
                'class' => WalletTypeClass::class
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Wallet::class
        ]);
    }

}