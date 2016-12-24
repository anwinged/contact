<?php

namespace AppBundle\Service;

use AppBundle\Document\Catcher;
use AppBundle\Document\Hit;
use AppBundle\Value\ProcessingPair;
use Psr\Log\LoggerInterface;

class HitProcessor
{
    /**
     * @var HandlerManager
     */
    private $handlerManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param HandlerManager  $handlerManager
     * @param LoggerInterface $logger
     */
    public function __construct(
        HandlerManager $handlerManager,
        LoggerInterface $logger
    ) {
        $this->handlerManager = $handlerManager;
        $this->logger = $logger;
    }

    /**
     * @param ProcessingPair $pair
     */
    public function process(ProcessingPair $pair)
    {
        /** @var Hit $hit */
        $hit = $pair->getHit();

        /** @var Catcher $catcher */
        $catcher = $pair->getCatcher();

        $alias = $catcher->getHandlerAlias();
        $handler = $this->handlerManager->getHandler($alias);

        if ($handler === null) {
            $this->logger->warning(sprintf(
                'Handler for alias "%s" not found',
                $alias
            ));

            return;
        }

        $payload = $hit->getPayload();
        $this->logger->info(sprintf('Call "%s" handler', $alias), ['data' => $payload]);

        $handler->handle($hit, $catcher->getHandlerConfiguration());
    }
}
