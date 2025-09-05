# Lingui

Lingui bridges your Laravel translations between backend and Inertia frontend with zero friction. It automatically generates locale JSON files and exposes them through Inertia — so you can use translation functions in your frontend like you already do in backend.

## Installation

Install the package via Composer:

```bash
composer require collaborar/lingui
```

The service provider will be automatically registered.

## Usage

### Generate Translation Files

Generate JSON translation files from your Laravel language files:

```bash
php artisan lingui:make-json
```

This command will create JSON files in your `public/lang` directory that can be consumed by your frontend.

### Frontend Integration

The package automatically shares translation data with Inertia, making your translations available in your frontend components.

## Requirements

- PHP 8.1+
- Laravel 12.26+
- Inertia.js

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
