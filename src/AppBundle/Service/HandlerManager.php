<?php

namespace AppBundle\Service;

use AppBundle\Handler\HandlerInterface;

class HandlerManager
{
    /**
     * @var HandlerInterface[]
     */
    private $handlers = [];

    public function addHandler(HandlerInterface $handler, string $alias)
    {
        $this->handlers[$alias] = $handler;
    }

    public function getAliases(): array
    {
        return array_keys($this->handlers);
    }

    public function getHandlers(): array
    {
        return $this->handlers;
    }

    public function getHandler(string $alias)
    {
        return $this->handlers[$alias] ?? null;
    }
}
