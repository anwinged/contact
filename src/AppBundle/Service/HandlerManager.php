<?php

namespace AppBundle\Service;

use AppBundle\Handler\HandlerInterface;

class HandlerManager
{
    /**
     * @var HandlerInterface[]
     */
    private $handlers = [];

    /**
     * @param HandlerInterface $handler
     * @param string           $alias
     */
    public function addHandler(HandlerInterface $handler, string $alias)
    {
        $this->handlers[$alias] = $handler;
    }

    /**
     * @return string[]
     */
    public function getAliases(): array
    {
        return array_keys($this->handlers);
    }

    /**
     * @return HandlerInterface[]
     */
    public function getHandlers(): array
    {
        return $this->handlers;
    }

    /**
     * @param string $alias
     *
     * @return HandlerInterface|null
     */
    public function getHandler(string $alias): ?HandlerInterface
    {
        return $this->handlers[$alias] ?? null;
    }
}
