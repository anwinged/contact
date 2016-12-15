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

    private $om;

    private $handlerManager;

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

    public function process(array $data, Project $project)
    {
        $target = $data[self::TARGET_KEY] ?? null;
        $payload = $data[self::PAYLOAD_KEY] ?? [];

        $catchers = $target !== null
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
        $hit->setData($data);

        $this->om->persist($hit);
        $this->om->flush();

        foreach ($catchers as $catcher) {
            /** @var Catcher $catcher */
            $alias = $catcher->getHandlerAlias();
            $handler = $this->handlerManager->getHandler($alias);

            if ($handler === null) {
                $this->logger->warning(sprintf(
                    'Handler for alias "%s" not found',
                    $alias
                ));
                continue;
            }

            $this->logger->info(sprintf('Call "%s" handler', $alias), ['data' => $data]);

            $handler->handle($project, $payload, $catcher->getHandlerConfiguration());
        }
    }
}
