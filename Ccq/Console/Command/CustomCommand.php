<?php

declare(strict_types=1);

namespace RLTSquare\Ccq\Console\Command;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\MessageQueue\PublisherInterface;
use RLTSquare\Ccq\Api\Data\DataInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CustomCommand extends Command
{
    private const VARIABLE1 = '-var1';
    private const VARIABLE2 = '-var2';

    /**
     * @var PublisherInterface
     */
    private PublisherInterface $publisher;
    /**
     * @var DataInterface
     */
    protected DataInterface $queue;
    /**
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * @param PublisherInterface $publisher
     * @param DataInterface $queue
     * @param LoggerInterface $logger
     */
    public function __construct(
        PublisherInterface $publisher,
        DataInterface $queue,
        LoggerInterface     $logger
    ) {
        $this->publisher = $publisher;
        $this->queue = $queue;
        $this->logger = $logger;
        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('rltsquare:hello:world')->setDescription('Custom Command');
        $this->setDefinition([
            new InputArgument(self::VARIABLE1, InputArgument::REQUIRED, "Variable1"),
            new InputArgument(self::VARIABLE2, InputArgument::REQUIRED, "Variable1")
            ]);
        parent::configure();
    }

    /**
     * Execute the command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $var1=$input->getArgument(self::VARIABLE1);
        $var2=$input->getArgument(self::VARIABLE2);

        try {
            if (!empty($var1) && !empty($var2)) {
                $this->queue->setData(["var1"=>$var1, "var2"=>$var2]);
                $this->publisher->publish('helloworld.topic', $this->queue);
                $output->writeln("$var1 $var2 added to the queue");
                $this->logger->info("job has been added");
            }
        } catch (LocalizedException $e) {
            $output->writeln(
                '<error>$e->getMessage()</error>'
            );
        }
        return 0;
    }
}
