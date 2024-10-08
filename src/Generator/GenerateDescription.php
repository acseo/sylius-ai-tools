<?php

declare(strict_types=1);

namespace ACSEO\SyliusAITools\Generator;

use Exception;
use OpenAI;
use OpenAI\Client;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class GenerateDescription
{
    private Client $client;

    public function __construct(
        string $apiKey,
        private string $model,
        private string $defaultLocale,
    ) {
        $this->client = OpenAI::client($apiKey);
    }

    public function fromPictures(array $pictures, string $locale = '', array $keywords = []): array
    {
        $locale = $locale ?: $this->defaultLocale;
        $prompt = $this->createPromptForPictures($locale, $keywords);
        $messages = $this->prepareMessagesForPictures($pictures, $prompt);

        return $this->execute($messages);
    }

    public function fromText(string $text, string $locale = '', array $keywords = []): array
    {
        $locale = $locale ?: $this->defaultLocale;
        $prompt = $this->createPromptForText($locale, $keywords, $text);
        $messages = [['role' => 'assistant', 'content' => $prompt]];

        return $this->execute($messages);
    }

    private function createPromptForPictures(string $locale, array $keywords): string
    {
        return str_replace(
            ['__KEYWORDS__', '__LOCALE__'],
            [implode(',', $keywords), $locale],
            <<<PROMPT
            Tu es un expert dans l'analyse d'images pour un site e-commerce. Ton métier consiste à regarder une image et générer un titre, une description courte, une description longue, la méta mot clé, et la méta description.

            Les contenus générés doivent être en lien avec l'image, et favoriser le SEO du site.

            Voici la liste des mots clés déjà existants sur le site : __KEYWORDS__.
            Respecte les consignes suivantes :
            - Tu peux utiliser ces mots clés ou en créer de nouveaux.
            - Texte en lien avec l'image.
            - Texte respectant les standards SEO, avec la bonne structure et nombre de caractères.
            - Les mots clés sont dans la langue : __LOCALE__.
            PROMPT
        );
    }

    private function createPromptForText(string $locale, array $keywords, string $text): string
    {
        return str_replace(
            ['__LOCALE__', '__KEYWORDS__', '__TEXT__'],
            [$locale, implode(',', $keywords), $text],
            <<<PROMPT
            Tu es un expert dans la rédaction de texte pour un site e-commerce. Ton métier consiste à lire un texte et générer un titre, une description courte, une description longue, la méta mot clé, et la méta description.

            Les contenus générés doivent être en lien avec le texte, et favoriser le SEO du site.

            Voici la liste des mots clés déjà existants sur le site : __KEYWORDS__.

            Respecte les consignes suivantes :
            - Tu peux utiliser ces mots clés ou en créer de nouveaux.
            - Texte en lien avec le texte.
            - Texte respectant les standards SEO, avec la bonne structure et nombre de caractères.
            - Les mots clés sont dans la langue : __LOCALE__.

            Le texte est le suivant : __TEXT__.
            PROMPT
        );
    }

    private function prepareMessagesForPictures(array $pictures, string $prompt): array
    {
        $messages = [['role' => 'assistant', 'content' => $prompt]];

        /** @var UploadedFile $picture */
        foreach ($pictures as $picture) {
            $dataUri = $this->encodeImage($picture);
            if (null !== $dataUri) {
                $messages[] = [
                    'role' => 'user',
                    'content' => [['type' => 'image_url', 'image_url' => ['url' => $dataUri]]],
                ];
            }
        }

        return $messages;
    }

    private function encodeImage(UploadedFile $picture): ?string
    {
        $type = strtolower($picture->getClientOriginalExtension());
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (!\in_array($type, $allowedTypes, true)) {
            return null;
        }

        $data = file_get_contents($picture->getPathname());

        return false !== $data ? 'data:image/' . $type . ';base64,' . base64_encode($data) : null;
    }

    private function execute(array $messages): array
    {
        try {
            $result = $this->client->chat()->create([
                'model' => $this->model,
                'messages' => $messages,
                'response_format' => $this->getResponseFormat(),
            ]);

            return $this->handleResponse($result);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    private function getResponseFormat(): array
    {
        return [
            'type' => 'json_schema',
            'json_schema' => [
                'name' => 'content',
                'schema' => [
                    'type' => 'object',
                    'properties' => [
                        'content' => [
                            'type' => 'array',
                            'items' => [
                                'type' => 'object',
                                'properties' => [
                                    'title' => ['type' => 'string'],
                                    'short_description' => ['type' => 'string'],
                                    'description' => ['type' => 'string'],
                                    'meta_description' => ['type' => 'string'],
                                    'meta_keywords' => ['type' => 'string'],
                                ],
                                'required' => ['title', 'short_description', 'description', 'meta_description', 'meta_keywords'],
                                'additionalProperties' => false,
                            ],
                        ],
                    ],
                    'additionalProperties' => false,
                    'required' => ['content'],
                ],
                'strict' => true,
            ],
        ];
    }

    private function handleResponse(object $result): array
    {
        if (empty($result->choices) || !isset($result->choices[0]->message->content)) {
            return [];
        }

        $content = $result->choices[0]->message->content;

        return json_decode($content, true);
    }
}
