<?php
namespace App\Bootstrap;

use Controllers;

class Web extends Base
{
    public function register()
    {
        parent::register();
        $app = $this->app();
        $app->mount('/', new Controllers\HomepageController);
    }
}