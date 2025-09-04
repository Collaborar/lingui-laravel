<?php

namespace Lingui;

use Lingui\Exceptions\JsonFileNotFound;
use Lingui\Support\ReadsJson;

class I18N
{
    use ReadsJson {
        load as loadJson;
    }

    /**
     * JSON files path.
     *
     * @var string
     */
    protected string $path;

    /**
     * The current locale.
     *
     * @var string
     */
    protected string $locale;

    /**
     * Create a new i18n provider instance.
     *
     * @param string $path
     * @param string $locale
     */
    public function __construct(string $path, string $locale)
    {
        $this->path = $path;
        $this->locale = $locale;
    }

    /**
     * Retrieve all translation strings.
     *
     * @return array
     */
    public function translation(): array
    {
        return $this->getAll();
    }

    /**
     * {@inheritDoc}
     */
    protected function getJsonPath(): string
    {
        $path = $this->path;
        $locale = $this->locale;

        return app()->basePath("{$path}/{$locale}.json");
    }

    /**
     * {@inheritDoc}
     */
    protected function load(string $file)
    {
        try {
            return $this->loadJson( $file );
        } catch (JsonFileNotFound $e) {
            //
        }
    }
}
