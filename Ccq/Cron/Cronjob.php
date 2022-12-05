<?php

declare(strict_types=1);

namespace RLTSquare\Ccq\Cron;

use Exception;
use Psr\Log\LoggerInterface;

class Cronjob
{
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Write to system.log
     *
     * @return void
     */
    public function execute()
    {
        try {
            $this->logger->info('hello world !!');
        } catch (Exception $e) {
            $this->logger->critical($e->getMessage());
        }
    }
}
