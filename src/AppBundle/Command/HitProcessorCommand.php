<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class HitProcessorCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:hit:process')
            ->setDescription('Hello PhpStorm');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $hitQueue = $this->getContainer()->get('app.hit_queue');
        $hitProcessor = $this->getContainer()->get('app.hit_processor');

        while (($pair = $hitQueue->pop()) !== null) {
            $hitProcessor->process($pair);

            $output->writeln(sprintf(
                'Done hit %s with catcher %s (%s)',
                $pair->getHit()->getId(),
                $pair->getCatcher()->getId(),
                $pair->getCatcher()->getHandlerAlias()
            ));
        }
    }
}
