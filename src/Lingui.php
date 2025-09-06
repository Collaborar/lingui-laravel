<?php

namespace Lingui;

use function Illuminate\Filesystem\join_paths;

class Lingui
{
    /**
     * Get the inertia props for the application.
     */
    public static function inertiaProps(): array
    {
        return [
            'i18n' => [
                'locale' => app()->getLocale(),
                'locales' => self::locales(),
                'translation' => self::translation(),
            ],
        ];
    }

    /**
     * Get the translation for a given locale.
     */
    public static function translation(?string $locale = null): array
    {
        $files = app('files');
        $file = join_paths(self::dir(), $locale ?? app()->getLocale().'.json');

        if (! $files->exists($file)) {
            return [];
        }

        $content = $files->get($file);
        $decoded = json_decode($content, true);
        $error = json_last_error();

        if ($error !== JSON_ERROR_NONE) {
            throw new \Exception('The required '.basename($file).' file is not a valid JSON (error code '.$error.')');
        }

        return $decoded;
    }

    /**
     * Get all locales available in the application.
     */
    public static function locales(): array
    {
        $files = app('files');
        $source = lang_path();

        // Get locales based on directories. E.g. `lang/en/*.php`
        $locales = collect($files->directories($source))
            ->reject(fn (string $path): bool => $files->isEmptyDirectory($path))
            ->map(fn (string $path): string => basename($path))
            ->toArray();

        // Get locales based on JSON files. E.g. `lang/es.json`
        // Merge boths without repeating locales.
        $locales = array_merge(
            $locales,
            collect(glob(join_paths($source, '*.json')))
                ->map(fn (string $file): string => pathinfo($file, PATHINFO_FILENAME))
                ->reject(fn (string $locale): bool => in_array($locale, $locales))
                ->toArray()
        );

        sort($locales);

        return $locales;
    }

    /**
     * Retrieve the directory path for the JSON files.
     */
    public static function dir(): string
    {
        return join_paths(public_path(), 'lang');
    }
}
