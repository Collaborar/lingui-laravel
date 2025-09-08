<?php

namespace Lingui\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Lingui\Lingui;
use Symfony\Component\Console\Attribute\AsCommand;

use function Illuminate\Filesystem\join_paths;

#[AsCommand('lingui:make-json')]
class MakeJson extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'lingui:make-json {lang-path?} {--locale=*}';

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
     */
    public function handle(): int
    {
        if (! $this->files->exists($this->langPath())) {
            $this->error('Lang directory not found. Please run `php artisan lang:publish` to publish the lang files.');

            return self::FAILURE;
        }

        $this->files->ensureDirectoryExists($this->dest());

        foreach ($this->locales() as $locale) {
            $json = $this->makeJson($locale);
            $dest = join_paths($this->dest(), "{$locale}.json");

            $this->files->put($dest, $json);
        }

        $this->info('Translation files generated in '.$this->dest());

        return self::SUCCESS;
    }

    /**
     * Make the JSON file content.
     */
    protected function makeJson(string $locale): string
    {
        $source = join_paths($this->langPath(), $locale);
        $fromPhps = $this->fromPhps($source);
        $fromJson = $this->fromJson($source.'.json');
        $json = array_merge($fromPhps, $fromJson);

        return json_encode($json, JSON_PRETTY_PRINT);
    }

    /**
     * Get the PHP strings from the path.
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
     */
    protected function fromJson(string $file): array
    {
        if (! $this->files->exists($file)) {
            return [];
        }

        $content = $this->files->get($file);
        $decoded = json_decode($content, true);
        $error = json_last_error();

        if ($error !== JSON_ERROR_NONE) {
            throw new Exception('The required '.basename($file).' file is not a valid JSON (error code '.$error.')');
        }

        return $decoded;
    }

    /**
     * Get the locales.
     */
    protected function locales(): array
    {
        return $this->option('locale') ?: Lingui::locales();
    }

    /**
     * Get the language path.
     */
    protected function langPath(): string
    {
        return $this->argument('lang-path') ?? lang_path();
    }

    /**
     * Retrieve the destination path.
     */
    protected function dest(): string
    {
        return Lingui::dir();
    }
}
