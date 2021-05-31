<?php

namespace PHPWarrior;

use RuntimeException;

/**
 * Class PlayerGenerator
 *
 * @package PHPWarrior
 */
class PlayerGenerator
{
    public $level;
    public $previousLevel;

    /**
     * Class constrictor.
     *
     * @param $level
     */
    public function __construct($level)
    {
        $this->level = $level;
    }

    /**
     * Previous level.
     */
    public function previousLevel(): Level
    {
        if (!$this->previousLevel) {
            $this->previousLevel = new Level($this->level->profile, $this->level->number - 1);
        }

        return $this->previousLevel;
    }

    /**
     * Generate.
     */
    public function generate(): void
    {
        if ($this->level->number === 1) {
            if (
                ! file_exists($this->level->playerPath())
                && ! mkdir($concurrentDirectory = $this->level->playerPath(), 0777, true)
                && ! is_dir($concurrentDirectory)
            ) {
                throw new RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
            }

            copy($this->templatePath() . '/player.php', $this->level->playerPath() . '/player.php');
        }

        file_put_contents(
            $this->level->playerPath() . '/README',
            $this->readTemplate($this->templatePath() . '/README.php')
        );
    }

    /**
     * Template path
     */
    public function templatePath(): string
    {
        return dirname(__DIR__, 2) . '/templates';
    }

    public function readTemplate(string $path): string
    {
        ob_start();
        include($path);

        return ob_get_clean();
    }
}
