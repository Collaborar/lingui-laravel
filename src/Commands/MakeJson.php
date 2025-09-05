<?php

namespace Lingui\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Attribute\AsCommand;
use function Illuminate\Filesystem\join_paths;
use Lingui\Lingui;

#[AsCommand('lingui:make-json')]
class MakeJson extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'lingui:make-json';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Combine PHP and JSON files into single JSON file';

    public function __construct(
        private Filesystem $files
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->files->ensureDirectoryExists($this->dest());

        $locales = Lingui::locales();

        foreach ($locales as $locale) {
            $json = $this->makeJson($locale);
            $dest = join_paths($this->dest(), "{$locale}.json");

            $this->files->put($dest, $json);
        }

        info('[Lingui] Made JSON files in '.$this->dest());
        return self::SUCCESS;
    }

    /**
     * Make the JSON file content.
     *
     * @param string $locale
     * @return string
     */
    protected function makeJson(string $locale): string
    {
        $source = join_paths(lang_path(), $locale);
        $fromPhps = $this->fromPhps($source);
        $fromJson = $this->fromJson($source . ".json");
        $json = array_merge($fromPhps, $fromJson);

        return json_encode($json, JSON_PRETTY_PRINT);
    }

    /**
     * Get the PHP strings from the path.
     *
     * @param string $path
     * @return array
     */
    protected function fromPhps(string $path): array
    {
        return collect(glob(join_paths($path, '*.php')))
            ->mapWithKeys(function (string $file) {
                $key = basename($file, '.php');
                $value = require $file;
                return [$key => is_array($value) ? $value : []];
            })
            ->toArray();
    }

    /**
     * Get the JSON strings from the file.
     *
     * @param string $file
     * @return array
     */
    protected function fromJson(string $file): array
    {
        if (!$this->files->exists($file)) {
            return [];
        }

        $content = $this->files->get($file);
        $decoded = json_decode($content, true);
        $error = json_last_error();

        if ($error !== JSON_ERROR_NONE) {
            throw new \Exception('The required '.basename($file). ' file is not a valid JSON (error code '.$error.')');
        }

        return $decoded;
    }

    /**
     * Retrieve the destination path.
     *
     * @return string
     */
    protected function dest(): string
    {
        return Lingui::dir();
    }
}
