<?php

namespace PHPWarrior;

/**
 * Class Space
 *
 * @package PHPWarrior
 */
class Space
{
    /**
     * Space constructor.
     *
     * @param $floor
     * @param int $x
     * @param int $y
     */
    public function __construct($floor, $x, $y)
    {
        $this->floor = $floor;
        $this->x = $x;
        $this->y = $y;
    }

    public function isWall()
    {
        return $this->floor->isOutOfBounds($this->x, $this->y);
    }

    public function isWarrior()
    {
        return is_a($this->unit(), 'PHPWarrior\Units\Warrior');
    }

    public function isGolem()
    {
        return is_a($this->unit(), 'PHPWarrior\Units\Golem');
    }

    public function isPlayer()
    {
        return ($this->isWarrior() || $this->isGolem());
    }

    public function isEnemy()
    {
        return ($this->unit() && ! $this->isPlayer() && ! $this->is_captive());
    }

    public function isCaptive()
    {
        return ($this->unit() && $this->unit()->isBound());
    }

    public function isEmpty()
    {
        return (is_null($this->unit()) && ! $this->isWall());
    }

    public function isStairs()
    {
        return ($this->floor->stairsLocation == $this->location());
    }

    public function isTicking()
    {
        return ($this->unit() && array_search('explode', array_keys($this->unit()->abilities())) !== false);
    }

    public function unit()
    {
        return $this->floor->get($this->x, $this->y);
    }

    public function location(): array
    {
        return [$this->x, $this->y];
    }

    public function character(): string
    {
        if ($this->unit()) {
            return $this->unit()->character();
        }

        if ($this->isStairs()) {
            return '>';
        }

        return ' ';
    }

    public function __ToString(): string
    {
        if ($this->unit()) {
            return (string) $this->unit();
        }

        if ($this->is_wall()) {
            return __('wall');
        }

        return __('nothing');
    }
}
