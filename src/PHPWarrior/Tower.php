<?php

namespace PHPWarrior;

/**
 * Class Tower
 *
 * @package PHPWarrior
 */
class Tower
{
    public string $path;

    /**
     * Tower constructor.
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function name(): string
    {
        return basename($this->path);
    }

    public function __toString(): string
    {
        return $this->name();
    }
}
