<?php

declare(strict_types=1);

namespace ACSEO\SyliusAITools\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractProductDescriptionFormType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('keywords', TextType::class, [
                'label' => 'sylius.ui.form.keywords_label',
                'help' => 'sylius.ui.form.keywords_help',
                'required' => false,
            ])
            ->add('locale', HiddenType::class, [
                'attr' => [
                    'value' => $options['locale'] ?? '',
                ],
            ])
            ->add('resource', HiddenType::class, [
                'attr' => [
                    'value' => $options['resource'] ?? '',
                ],
            ])
        ;

        $this->buildCustomFormFields($builder, $options);
    }

    /**
     * Add custom fields specific to the child class.
     */
    abstract protected function buildCustomFormFields(FormBuilderInterface $builder, array $options): void;

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'locale' => null,
            'resource' => null,
        ]);
    }
}
