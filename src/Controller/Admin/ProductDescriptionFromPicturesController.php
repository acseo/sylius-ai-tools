<?php

declare(strict_types=1);

namespace ACSEO\SyliusAITools\Controller\Admin;

use ACSEO\SyliusAITools\Form\ProductDescriptionFromPicturesFormType;

class ProductDescriptionFromPicturesController extends AbstractProductDescriptionController
{
    protected function getFormType(): string
    {
        return ProductDescriptionFromPicturesFormType::class;
    }

    protected function getTemplatePath(): string
    {
        return '@SyliusAITools/admin/GenerateDescriptionFromPictures/index.html.twig';
    }

    protected function generateDescriptions(array $data): array
    {
        return $this->descriptionManager->generateDescriptionsFromPictures($data);
    }
}
