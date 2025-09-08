# Lingui

Lingui bridges your Laravel translations between backend and Inertia frontend with zero friction. It automatically generates locale JSON files and exposes them through Inertia — so you can use translation functions in your frontend like you already do in backend.

## Installation
To get started, install Lingui via the Composer package manager:

```bash
composer require collaborar/lingui
```

Next, install the [Lingui Vite plugin](https://github.com/Collaborar/vite-plugin-lingui) to ensure that your translations are generated during Vite's build step and also whenever your files change while running the Vite's dev server.

First, install the plugin via your package manager:
```bash
npm i -D @collaborar/vite-plugin-lingui
```

Then, update your application's `vite.config.js` file to watch for changes to your application's lang files:
```js
import { lingui } from "@collaborar/vite-plugin-lingui";

export default defineConfig({
  plugins: [
    lingui(),
    // ...
  ],
});
```
You can read about all of the plugin's configuration options in the [documentation](https://github.com/Collaborar/vite-plugin-lingui).


## Generating Translation Files
The ``lingui:make-json` command can be used to generate JSON files containing all your translations grouped by locale:
```bash
php artisan lingui:make-json

```

The `--locale` option may be used to generate translation for specific locale only:

```bash
php artisan lingui:make-json --locale=pt --locale=en
```

You can safely `.gitignore` the `public/lang` directory as he's completely re-generated on every build.

### Frontend Integration

The package automatically shares translation data with Inertia, making your translations available in your frontend components.
You can check the data in your Shared Data, the structure is:

```js
{
  i18n: {
    locale: 'en',
    locales: ['en', 'pt'], // Available locales
    translation: {
      auth: {
        failed: 'These credentials do not match our records.',
      }
      // All your translation goes here...
    }
  }
}
```

> [!NOTE]
> We aim to develop a react and vue package for translation usage.

## Requirements

- PHP 8.2+
- Laravel 11+
- Inertia.js

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
