<?php

namespace Tests;

use Illuminate\Foundation\Application;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return Application
     */
    public function createApplication() : Application
    {
        return require __DIR__.'/../bootstrap/app.php';
    }
}
