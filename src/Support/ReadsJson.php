<?php

namespace Lingui\Support;

use Lingui\Exceptions\{ JsonFileNotFound, JsonFileInvalid };

trait ReadsJson
{
    /**
     * Cache.
     *
     * @var array|null
     */
    protected ?array $cache = null;

    /**
     * Get the path to the JSON that should be read.
     *
     * @return string
     */
    abstract protected function getJsonPath(): string;

    /**
     * Load the json file.
     *
     * @param string $file
     *
     * @return array
     */
    protected function load(string $file)
    {
        $files = app('files');

        if (!$files->exists($file)) {
            throw new JsonFileNotFound('The required ' . basename($file) . ' file is missing.');
        }

        $contents = $files->get($file);
        $json = json_decode($contents, true);
        $jsonError = json_last_error();

        if ($jsonError !== JSON_ERROR_NONE ) {
            throw new JsonFileInvalid( 'The required ' . basename( $file ) . ' file is not valid JSON (error code ' . $jsonError . ').' );
        }

        return $json;
    }

    /**
     * Get the entire json array.
     *
     * @return array
     */
    protected function getAll(): array
    {
        if ($this->cache === null) {
            $this->cache = $this->load($this->getJsonPath());
        }

        return $this->cache;
    }
}
