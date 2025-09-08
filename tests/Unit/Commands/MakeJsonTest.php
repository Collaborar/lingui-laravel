<?php

namespace Lingui\Tests;

class MakeJsonTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app->usePublicPath($this->tempPath().'/public');
        $this->app->useLangPath(self::LANG_PATH);
    }

    public function test_success_on_create_json_files(): void
    {
        $this->artisan('lingui:make-json')
            ->expectsOutput('Translation files generated in '.$this->app->publicPath().'/lang')
            ->assertExitCode(0);
    }

    public function test_combine_php_and_json_translations(): void
    {
        $this->artisan('lingui:make-json');

        $json = $this->files->get($this->tempPath().'/public/lang/pt.json');
        $translations = json_decode($json, true);

        $this->assertArrayHasKey('auth', $translations);
        $this->assertEquals('A senha fornecida está incorreta.', $translations['auth']['password']);

        $this->assertArrayHasKey('Hello :Name', $translations);
        $this->assertEquals('Olá :Name', $translations['Hello :Name']);
    }

    public function test_create_specified_locale_only(): void
    {
        $this->artisan('lingui:make-json --locale=pt');

        $generateds = $this->files->glob($this->tempPath().'/public/lang/*.json');

        $this->assertEquals([$this->tempPath().'/public/lang/pt.json'], $generateds);
    }
}
