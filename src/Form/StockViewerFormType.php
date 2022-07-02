<?php

namespace App\Form;

use App\Entity\FormDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StockViewerFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('companySymbol', ChoiceType::class, [
                 'label' => 'Company Symbol',
                 'placeholder' => 'Choose a Company',
                 'required' => true,
                 'choices' => FormDto::getCompanySymbols(),
            ])
            ->add('startDate', DateType::class, [
                'widget' => 'single_text',

            ])
            ->add('endDate', DateType::class, [
                'widget' => 'single_text',

            ])
            ->add('email', EmailType::class)
            ->add('submit', SubmitType::class, [
                'label' => 'Show',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FormDto::class,
        ]);
    }
}
