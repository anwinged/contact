<?php

namespace AppBundle\Handler;

use AppBundle\Document\Project;

interface HandlerInterface
{
    /**
     * @return string
     */
    public function getCaption(): string;

    /**
     * @return string
     */
    public function getDescription(): string;

    /**
     * @return array
     */
    public function getConfiguration(): array;

    /**
     * @param Project $project
     * @param array   $data
     * @param array   $configuration
     */
    public function handle(Project $project, array $data, array $configuration): void;
}
