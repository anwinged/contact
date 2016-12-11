<?php

namespace AppBundle\Document;

use AppBundle\Enum\HitState;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

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
     * @MongoDB\Field(type="hash")
     *
     * @var array
     */
    private $data = [];

    /**
     * @MongoDB\Field(type="string")
     *
     * @var string
     */
    private $state;

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
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->data = $data;
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
