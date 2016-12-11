<?php

namespace AppBundle\Document;

use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\ODM\MongoDB\PersistentCollection;

/**
 * @MongoDB\Document
 */
class Project
{
    /**
     * @MongoDB\Id
     *
     * @var string
     */
    private $id;

    /**
     * @MongoDB\ReferenceOne(targetDocument="User", inversedBy="projects")
     *
     * @var User
     */
    private $user;

    /**
     * @MongoDB\Field(type="string")
     *
     * @var string
     */
    private $name = '';

    /**
     * @MongoDB\ReferenceMany(targetDocument="Catcher", mappedBy="project")
     *
     * @var PersistentCollection
     */
    private $catchers;

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
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return PersistentCollection
     */
    public function getCatchers(): PersistentCollection
    {
        return $this->catchers;
    }

    /**
     * @param PersistentCollection $catchers
     */
    public function setCatchers(PersistentCollection $catchers)
    {
        $this->catchers = $catchers;
    }

    /**
     * @param string $target
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCatchersByTarget(string $target): Collection
    {
        $callback = function (Catcher $catcher) use ($target) {
            return $catcher->getTarget() === $target;
        };

        return $this->catchers->filter($callback);
    }
}
