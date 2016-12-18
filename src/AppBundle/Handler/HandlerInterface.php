<?php

namespace AppBundle\Handler;

use AppBundle\Document\Hit;

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
     * @param Hit   $hit
     * @param array $configuration
     */
    public function handle(Hit $hit, array $configuration): void;
}
