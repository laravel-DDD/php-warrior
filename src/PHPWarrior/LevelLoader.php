<?php

namespace PHPWarrior;

use PHPWarrior\Units\Base;
use PHPWarrior\Units\Warrior;

/**
 * Class LevelLoader
 *
 * @package PHPWarrior
 */
class LevelLoader
{
    /**
     * Class constructor
     *
     * @param $level
     * @param $load_path
     */
    public function __construct($level, $load_path)
    {
        $this->floor = new Floor();
        $this->level = $level;
        $this->level->floor = $this->floor;
        include $load_path;
    }

    /**
     * Description.
     */
    public function description($desc): void
    {
        $this->level->description = $desc;
    }

    /**
     * Tip.
     */
    public function tip($tip): void
    {
        $this->level->tip = $tip;
    }

    /**
     * Clue.
     */
    public function clue($clue): void
    {
        $this->level->clue = $clue;
    }

    /**
     * Time bonus.
     */
    public function timeBonus($bonus): void
    {
        $this->level->timeBonus = $bonus;
    }

    /**
     * Ace score.
     */
    public function aceScore($score): void
    {
        $this->level->ace_score = $score;
    }

    /**
     * Size.
     *
     */
    public function size($width, $height): void
    {
        $this->floor->width = $width;
        $this->floor->height = $height;
    }

    /**
     * Stairs.
     */
    public function stairs($x, $y): void
    {
        $this->floor->placeStairs($x, $y);
    }

    /**
     * unit.
     *
     * @param $unit
     * @param $x
     * @param $y
     * @param string $facing
     *
     * @return mixed
     */
    public function unit($unit, $x, $y, string $facing = ':north'): mixed
    {
        if (! $unit instanceof Base) {
            $camel = $this->unitToConstant($unit);
            $unit = new $camel();
        }

        $this->floor->add($unit, $x, $y, $facing);
        //yield unit if block_given?
        return $unit;
    }

    /**
     * Warrior
     *
     * @param $x
     * @param $y
     * @param $facing
     *
     * @return mixed
     */
    public function warrior($x, $y, $facing)
    {
        return $this->level->setupWarrior(
            $this->unit(new Warrior(), $x, $y, $facing)
        );
    }

    /**
     * Unit to constant
     *
     * @param  mixed $name Yhe name of the unit in the level.
     * @return string
     */
    public function unitToConstant($name): string
    {
        $camel = '';
        $name = str_replace(':', '', $name);
        foreach (explode('_', $name) as $str) {
            $camel .= ucfirst($str);
        }
        return 'PHPWarrior\Units\\' . $camel;
    }
}
