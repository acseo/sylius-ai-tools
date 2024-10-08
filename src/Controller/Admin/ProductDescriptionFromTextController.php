<?php

declare(strict_types=1);

namespace ACSEO\SyliusAITools\Controller\Admin;

use ACSEO\SyliusAITools\Form\ProductDescriptionFromTextFormType;

class ProductDescriptionFromTextController extends AbstractProductDescriptionController
{
    protected function getFormType(): string
    {
        return ProductDescriptionFromTextFormType::class;
    }

    protected function getTemplatePath(): string
    {
        return '@SyliusAITools/admin/GenerateDescriptionFromText/index.html.twig';
    }

    protected function generateDescriptions(array $data): array
    {
        return $this->descriptionManager->generateDescriptionsFromText($data);
    }
}
