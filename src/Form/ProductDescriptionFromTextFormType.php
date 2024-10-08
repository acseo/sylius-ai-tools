<?php

declare(strict_types=1);

namespace ACSEO\SyliusAITools\Form;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class ProductDescriptionFromTextFormType extends AbstractProductDescriptionFormType
{
    protected function buildCustomFormFields(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('text', TextareaType::class, [
            'label' => 'sylius.ui.form.text_label',
        ]);
    }
}
