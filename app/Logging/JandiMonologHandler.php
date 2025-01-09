<?php

namespace App\Logging;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use App\Services\JandiLogger;

class JandiMonologHandler extends AbstractProcessingHandler
{
    protected $jandiLogger;

    public function __construct(JandiLogger $jandiLogger, $level = Logger::DEBUG, bool $bubble = true)
    {
        parent::__construct($level, $bubble);
        $this->jandiLogger = $jandiLogger;
    }

    protected function write(array $record): void
    {
        $this->jandiLogger->sendLog(
            $record['level_name'],
            $record['message'],
            $record['context'],
            $record
        );
    }
}
