<?php

namespace Lingui\Tests;

use Illuminate\Filesystem\Filesystem;
use Lingui\LinguiServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected Filesystem $files;

    protected const LANG_PATH = __DIR__.'/Fixtures/lang';

    protected function getPackageProviders($app): array
    {
        return [
            LinguiServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.locale', 'en');
        $app['config']->set('app.fallback_locale', 'en');
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->files = new Filesystem;
    }

    protected function tearDown(): void
    {
        $this->cleanUp();

        parent::tearDown();
    }

    protected function tempPath(): string
    {
        return __DIR__.'/temp';
    }

    protected function cleanUp(): void
    {
        $path = $this->tempPath();

        if ($this->files->exists($path)) {
            $this->files->deleteDirectory($path);
        }
    }
}
