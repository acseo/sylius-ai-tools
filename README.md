<p align="center">
    <a href="https://www.acseo.fr" target="_blank">
        ACSEO
    </a>
</p>

<h1 align="center">
Sylius AI Tools
<br />
    <a href="https://packagist.org/packages/acseo/sylius-ai-tools" title="License" target="_blank">
        <img src="https://img.shields.io/packagist/l/acseo/sylius-ai-tools.svg" />
    </a>
    <a href="https://packagist.org/packages/acseo/sylius-ai-tools" title="Version" target="_blank">
        <img src="https://img.shields.io/packagist/v/acseo/sylius-ai-tools.svg" />
    </a>
</h1>

<img src="capture.gif">
<p>Use AI to improve eShop experience</p>

## Installation


To integrate the **Sylius AI Tools** into your project, follow these steps:

### Step 1: Install the Package

Install **Sylius AI Tools** using Composer. We recommend using stable, supported, and up-to-date versions of packages. Run the following command in your terminal:
```bash
$ composer require acseo/sylius-ai-tools
```

### Step 2: Register the Plugin

You need to register the plugin in your Symfony application. Open the config/bundles.php file and add the following line:
```php
return [
    ACSEO\SyliusAITools\SyliusAITools::class => ['all' => true],
];
```

### Step 3: Import Required Configuration

   Next, import the required configuration by adding the service definitions to your config/packages/_sylius.yaml file. Append the following lines:
```yaml
# config/packages/_sylius.yaml
imports:
    - { resource: "@SyliusAITools/Resources/config/services.yaml" }
```


### Step 4: Import Routing Configuration
To make the routes available, you need to import the routing configuration. Add the following lines to your config/routes.yaml file:

```yaml
# config/routes.yaml
sylius_admin_ai_tools:
    resource: "@SyliusAITools/Resources/config/routes/sylius_admin.yaml"
```

### Step 5: Finalize the Installation
To complete the installation process, clear the cache and update the database schema. Execute the following command:

```bash
$ bin/console cache:clear
```

### Step 6: Configure Twig Paths
Update your Twig configuration to include the plugin's view templates. In your config/packages/twig.yaml, add the following path configuration:
```bash
twig:
    paths:
        '%kernel.project_dir%/vendor/acseo/sylius-ai-tools/src/Resources/views': ~
```

### Step 7: Add Plugin Templates
Copy the plugin templates into your project's template directory. Run the following command:
```bash
cp -R vendor/acseo/sylius-ai-tools/src/Resources/views/bundles/ templates/bundles/
```

### Step 8: Configure the Environment
Finally, set up your environment by adding the OpenAI API key to your .env file. Open the file and add the following line:
```bash
OPENAI_API_KEY=your_api_key_here
```
