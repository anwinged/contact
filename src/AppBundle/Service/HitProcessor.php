<?php

namespace AppBundle\Service;

use AppBundle\Document\Catcher;
use AppBundle\Document\Hit;
use AppBundle\Document\Project;
use AppBundle\Enum\HitState;
use Doctrine\Common\Persistence\ObjectManager;

class HitProcessor
{
    private $om;

    private $handlerManager;

    public function __construct(ObjectManager $om, HandlerManager $handlerManager)
    {
        $this->om = $om;
        $this->handlerManager = $handlerManager;
    }

    public function process(array $data, Project $project)
    {
        $target = $data['target'] ?? null;
        unset($data['target']);

        $catchers = $target !== null
            ? $project->getCatchersByTarget((string) $target)
            : [];

        $hit = new Hit();
        $hit->setProject($project);
        $hit->setTime(new \DateTime());
        $hit->setState(count($catchers) !== 0 ? HitState::CAPTURED() : HitState::MISSED());
        $hit->setData($data);

        $this->om->persist($hit);
        $this->om->flush();

        foreach ($catchers as $catcher) {
            /** @var Catcher $catcher */
            $handler = $this->handlerManager->getHandler($catcher->getHandlerAlias());
            if ($handler === null) {
                continue;
            }

            $handler->handle($hit, $catcher->getHandlerConfiguration());
        }
    }
}
