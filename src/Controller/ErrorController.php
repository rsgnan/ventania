<?php

namespace App\Controller;

use App\Core\ViewController;

class ErrorController extends ViewController
{
    public function notFound(): void
    {
        http_response_code(404);

        $this->render('errors/404', []);
    }
}
