<?php

$finder = Symfony\CS\Finder::create()
    ->in(__DIR__.'/src')
;

return Symfony\CS\Config::create()
    ->level(Symfony\CS\FixerInterface::SYMFONY_LEVEL)
    ->fixers(['short_array_syntax'])
    ->finder($finder)
;
