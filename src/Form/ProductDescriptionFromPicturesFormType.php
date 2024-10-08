<?php

declare(strict_types=1);

namespace ACSEO\SyliusAITools\Form;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;

class ProductDescriptionFromPicturesFormType extends AbstractProductDescriptionFormType
{
    protected function buildCustomFormFields(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('pictures', FileType::class, [
            'label' => 'sylius.ui.form.image_label',
            'multiple' => true,
        ]);
    }
}
