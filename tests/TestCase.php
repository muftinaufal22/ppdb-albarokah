<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
// PENTING: Import middleware CSRF
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
        
        // PENTING: Nonaktifkan CSRF check saat testing
        $this->withoutMiddleware([VerifyCsrfToken::class]);
    }
}