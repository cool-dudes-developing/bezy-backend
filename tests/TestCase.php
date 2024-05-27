<?php

namespace Tests;

use Artisan;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('migrate', ['-vvv' => true]);
        Artisan::call('passport:install', ['-vvv' => true]);
    }
}
