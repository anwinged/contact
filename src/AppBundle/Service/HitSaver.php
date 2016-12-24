<?php

namespace AppBundle\Service;

use AppBundle\Document\Hit;
use AppBundle\Document\Project;
use AppBundle\Enum\HitState;
use AppBundle\Value\ProcessingPair;
use Doctrine\Common\Persistence\ObjectManager;
use Psr\Log\LoggerInterface;

class HitSaver
{
    const TARGET_KEY = 'target';

    const PAYLOAD_KEY = 'payload';

    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @var HitQueue
     */
    private $hitQueue;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        ObjectManager $om,
        HitQueue $hitQueue,
        LoggerInterface $logger
    ) {
        $this->om = $om;
        $this->hitQueue = $hitQueue;
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
            $this->hitQueue->push(new ProcessingPair($hit, $catcher));
        }

        return $hit;
    }
}
