<?php

namespace App\Logging;

use App\Services\JandiLogger;
use Monolog\Logger;

class JandiLoggerHandler
{
    public function __invoke(array $config)
    {

        return new Logger('jandi', [
            new JandiMonologHandler(app(JandiLogger::class))
        ]);
    }
}
