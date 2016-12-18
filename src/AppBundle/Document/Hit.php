<?php

namespace AppBundle\Document;

use AppBundle\Enum\HitState;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\ORM\PersistentCollection;

/**
 * @MongoDB\Document(repositoryClass="AppBundle\Repository\HitRepository")
 */
class Hit
{
    /**
     * @MongoDB\Id
     *
     * @var string
     */
    private $id;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Project", inversedBy="catchers")
     *
     * @var Project
     */
    private $project;

    /**
     * @MongoDB\Field(type="timestamp")
     *
     * @var \MongoTimestamp
     */
    private $timestamp;

    /**
     * @MongoDB\Field(type="string")
     *
     * @var string
     */
    private $target;

    /**
     * @MongoDB\Field(type="hash")
     *
     * @var array
     */
    private $payload = [];

    /**
     * @MongoDB\ReferenceMany(targetDocument="Catcher")
     *
     * @var Collection
     */
    private $catchers;

    /**
     * @MongoDB\Field(type="string")
     *
     * @var string
     */
    private $state;

    public function __construct()
    {
        $this->catchers = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return Project
     */
    public function getProject(): Project
    {
        return $this->project;
    }

    /**
     * @param Project $project
     */
    public function setProject(Project $project)
    {
        $this->project = $project;
    }

    /**
     * @return \DateTime
     */
    public function getTime(): \DateTime
    {
        $date = new \DateTime();
        $date->setTimestamp($this->timestamp->sec);

        return $date;
    }

    /**
     * @param \DateTime $time
     */
    public function setTime(\DateTime $time)
    {
        $this->timestamp = new \MongoTimestamp($time->getTimestamp());
    }

    /**
     * @return string
     */
    public function getTarget(): string
    {
        return $this->target ?? '';
    }

    /**
     * @param string $target
     */
    public function setTarget(string $target)
    {
        $this->target = $target;
    }

    /**
     * @return array
     */
    public function getPayload(): array
    {
        return $this->payload ?? [];
    }

    /**
     * @param array $payload
     */
    public function setPayload(array $payload)
    {
        $this->payload = $payload;
    }

    /**
     * @param Catcher $catcher
     */
    public function addCatcher(Catcher $catcher)
    {
        $this->catchers->add($catcher);
    }

    /**
     * @return Collection
     */
    public function getCatchers(): Collection
    {
        return $this->catchers;
    }

    /**
     * @param Collection $catchers
     */
    public function setCatchers(Collection $catchers)
    {
        $this->catchers = $catchers;
    }

    /**
     * @return HitState
     */
    public function getState(): HitState
    {
        return new HitState($this->state);
    }

    /**
     * @param HitState $state
     */
    public function setState(HitState $state)
    {
        $this->state = $state->getValue();
    }
}
