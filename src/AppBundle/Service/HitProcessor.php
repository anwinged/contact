<?php

namespace AppBundle\Service;

use AppBundle\Document\Catcher;
use AppBundle\Document\Hit;
use AppBundle\Document\Project;
use AppBundle\Enum\HitState;
use Doctrine\Common\Persistence\ObjectManager;
use Psr\Log\LoggerInterface;

class HitProcessor
{
    const TARGET_KEY = 'target';

    const PAYLOAD_KEY = 'payload';

    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @var HandlerManager
     */
    private $handlerManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        ObjectManager $om,
        HandlerManager $handlerManager,
        LoggerInterface $logger
    ) {
        $this->om = $om;
        $this->handlerManager = $handlerManager;
        $this->logger = $logger;
    }

    /**
     * @param array   $data
     * @param Project $project
     *
     * @return Hit
     */
    public function process(array $data, Project $project): Hit
    {
        $target = $data[self::TARGET_KEY] ?? '';
        $payload = $data[self::PAYLOAD_KEY] ?? [];

        $catchers = $target !== ''
            ? $project->getCatchersByTarget((string) $target)
            : [];

        $state = count($catchers) !== 0
            ? HitState::CAPTURED()
            : HitState::MISSED()
        ;

        $hit = new Hit();
        $hit->setProject($project);
        $hit->setTime(new \DateTime());
        $hit->setState($state);
        $hit->setTarget($target);
        $hit->setPayload($payload);

        foreach ($catchers as $catcher) {
            $hit->addCatcher($catcher);
        }

        $this->om->persist($hit);
        $this->om->flush();

        foreach ($catchers as $catcher) {
            $this->applyCatcherHandler($catcher, $hit);
        }

        return $hit;
    }

    /**
     * @param Catcher $catcher
     * @param Hit     $hit
     */
    private function applyCatcherHandler(Catcher $catcher, Hit $hit): void
    {
        /** @var Catcher $catcher */
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
