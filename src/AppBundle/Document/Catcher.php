<?php

namespace AppBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document()
 */
class Catcher
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
     * @MongoDB\Field(type="string")
     *
     * @var string
     */
    private $target = '';

    /**
     * @MongoDB\Field(type="string")
     *
     * @var string
     */
    private $handlerAlias = '';

    /**
     * @MongoDB\Field(type="hash")
     *
     * @var array
     */
    private $handlerConfiguration = [];

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
     * @return string
     */
    public function getTarget(): string
    {
        return $this->target;
    }

    /**
     * @param string $target
     */
    public function setTarget(string $target)
    {
        $this->target = $target;
    }

    /**
     * @return string
     */
    public function getHandlerAlias(): string
    {
        return $this->handlerAlias;
    }

    /**
     * @param string $handlerAlias
     */
    public function setHandlerAlias(string $handlerAlias)
    {
        $this->handlerAlias = $handlerAlias;
    }

    /**
     * @return array
     */
    public function getHandlerConfiguration(): array
    {
        return $this->handlerConfiguration;
    }

    /**
     * @param array $handlerConfiguration
     */
    public function setHandlerConfiguration(array $handlerConfiguration)
    {
        $this->handlerConfiguration = $handlerConfiguration;
    }
}
