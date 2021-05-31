<?php

namespace PHPWarrior;

use PHPWarrior\Units\Warrior;

/**
 * Class Floor
 *
 * @package PHPWarrior
 */
class Floor
{
    public $width = 0;
    public $height = 0;
    public $units = [];
    public $stairs_location = [-1, -1];

    /**
     * Add.
     *
     * @param $unit
     * @param $x
     * @param $y
     *
     * @param null $direction
     */
    public function add($unit, $x, $y, $direction = null): void
    {
        $this->units[] = $unit;
        $unit->position = new Position($this, $x, $y, $direction);
    }

    /**
     * Place the stairs.
     *
     * @param $x
     * @param $y
     */
    public function placeStairs($x, $y): void
    {
        $this->stairs_location = [$x, $y];
    }

    /**
     * Stairs space.
     */
    public function stairsSpace(): mixed
    {
        return call_user_func_array([$this, 'space'], $this->stairs_location);
    }

    /**
     * Units.
     */
    public function units(): array
    {
        // todo? filter null
        return array_filter($this->units, static function ($v) {
            return ! is_null($v->position);
        });
    }

    /**
     * Other units.
     */
    public function otherUnits(): array
    {
        return array_filter($this->units, static function ($v): void {
            $v instanceof Warrior;
        });
    }

    /**
     * Get.
     *
     * @param $x
     * @param $y
     *
     * @return mixed
     */
    public function get($x, $y): mixed
    {
        foreach ($this->units as $v) {
            if (!is_null($v->position) && $v->position->isAt($x, $y)) {
                return $v;
            }
        }
    }

    /**
     * Space.
     *
     * @param $x
     * @param $y
     */
    public function space($x, $y): Space
    {
        return new Space($this, $x, $y);
    }

    /**
     * Is it out of bounds?
     *
     * @param $x
     * @param $y
     */
    public function isOutOfBounds($x, $y): bool
    {
        return ($x < 0 || $y < 0 || $x > ($this->width - 1) || $y > ($this->height - 1));
    }

    /**
     * Character
     */
    public function character(): string
    {
        $rows = [];
        $rows[] = ' ' . str_repeat('-', $this->width);

        for ($y = 0; $y < $this->height; $y++) {
            $row = '|';

            for ($x = 0; $x < $this->width; $x++) {
                $row .= $this->space($x, $y)->character();
            }
            $row .= '|';
            $rows[] = $row;
        }

        $rows[] = ' ' . str_repeat('-', $this->width);
        return implode("\n", $rows) . "\n";
    }

    /**
     * Get the unique units.
     */
    public function uniqueUnits(): array
    {
        $uniqueUnits = [];

        foreach ($this->units as $unit) {
            $uniqueUnits[(string)$unit] = $unit;
        }
        return $uniqueUnits;
    }
}
