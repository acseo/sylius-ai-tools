<?php

declare(strict_types=1);

namespace ACSEO\SyliusAITools\Manager;

use ACSEO\SyliusAITools\Generator\GenerateDescription;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;

class DescriptionManager
{
    public function __construct(
        private GenerateDescription $descriptionGenerator,
        private ProductRepositoryInterface $productRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function generateAndUpdateProductDescription(array $descriptions, string $resource, string $locale): ?ProductInterface
    {
        $product = $this->productRepository->find((int) $resource);

        if (!$product instanceof ProductInterface) {
            return null;
        }

        if (!empty($descriptions) && isset($descriptions['content'][0])) {
            $firstDescription = $descriptions['content'][0];
            $translation = $product->getTranslation($locale);
            $translation->setName($firstDescription['title'] ?? '');
            $translation->setDescription($firstDescription['description'] ?? '');
            $translation->setShortDescription($firstDescription['short_description'] ?? '');
            $translation->setMetaDescription($firstDescription['meta_description'] ?? '');
            $translation->setMetaKeywords($firstDescription['meta_keywords'] ?? '');

            $this->entityManager->persist($product);
            $this->entityManager->flush();
        }

        return $product;
    }

    public function generateDescriptionsFromText(array $data): array
    {
        $data['keywords'] = $this->prepareKeywords($data['keywords'] ?? '');

        return $this->generateContent(
            [$this->descriptionGenerator, 'fromText'],
            $data['text'] ?? '',
            $data['locale'] ?? '',
            $data['keywords']
        );
    }

    public function generateDescriptionsFromPictures(array $data): array
    {
        $data['keywords'] = $this->prepareKeywords($data['keywords'] ?? '');

        return $this->generateContent(
            [$this->descriptionGenerator, 'fromPictures'],
            $data['pictures'] ?? '',
            $data['locale'] ?? '',
            $data['keywords']
        );
    }

    private function prepareKeywords(string $keywords): array
    {
        return array_map('trim', explode(',', $keywords));
    }

    private function generateContent(callable $generationMethod, string|array $content, string $locale, array $keywords): array
    {
        return $generationMethod($content, $locale, $keywords);
    }
}
