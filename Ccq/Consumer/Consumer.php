<?php

declare(strict_types=1);

namespace RLTSquare\Ccq\Consumer;

use Exception;
use Psr\Log\LoggerInterface;

/**
 * Consumer class for rltsquare_hello_world job
 */
class Consumer
{
    /**
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * this method process the queue message
     * @return void
     */
    public function process(): void
    {
        try {
            $this->logger->info('hello world from rltsquare_hello_world queue job');
        } catch (Exception $e) {
            $this->logger->critical($e->getMessage());
        }
    }
}
