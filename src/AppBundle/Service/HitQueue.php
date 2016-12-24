<?php

namespace AppBundle\Service;

use AppBundle\Document\Catcher;
use AppBundle\Document\Hit;
use AppBundle\Value\ProcessingPair;
use Doctrine\Common\Persistence\ObjectManager;
use Pheanstalk\PheanstalkInterface;
use Psr\Log\LoggerInterface;

class HitQueue
{
    const HIT_QUEUE_NAME = 'hit';

    /**
     * @var PheanstalkInterface
     */
    private $pheanstalk;

    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param PheanstalkInterface $pheanstalk
     * @param ObjectManager       $om
     * @param LoggerInterface     $logger
     */
    public function __construct(
        PheanstalkInterface $pheanstalk,
        ObjectManager $om,
        LoggerInterface $logger
    ) {
        $this->pheanstalk = $pheanstalk;
        $this->om = $om;
        $this->logger = $logger;
    }

    /**
     * @param ProcessingPair $pair
     */
    public function push(ProcessingPair $pair)
    {
        $data = [
            'hitId' => $pair->getHit()->getId(),
            'catcherId' => $pair->getCatcher()->getId(),
        ];

        $serializedData = serialize($data);

        $this->pheanstalk
            ->useTube(self::HIT_QUEUE_NAME)
            ->put($serializedData)
        ;
    }

    /**
     * @return ProcessingPair|null
     */
    public function pop(): ?ProcessingPair
    {
        $job = $this->pheanstalk
            ->watch(self::HIT_QUEUE_NAME)
            ->ignore('default')
            ->reserve()
        ;

        $serializedData = $job->getData();

        $data = unserialize($serializedData);

        $hit = $this->om->getRepository(Hit::class)->find($data['hitId']);
        $catcher = $this->om->getRepository(Catcher::class)->find($data['catcherId']);

        if (!$hit) {
            $this->logger->warning(sprintf(
                'Hit with id = "%s" not found. Job rejected',
                $data['hitId']
            ));
            $this->pheanstalk->delete($job);

            return null;
        }

        if (!$catcher) {
            $this->logger->warning(sprintf(
                'Catcher with id = "%s" not found. Job rejected',
                $data['catcherId']
            ));
            $this->pheanstalk->delete($job);

            return null;
        }

        $this->pheanstalk->delete($job);

        return new ProcessingPair($hit, $catcher);
    }
}
