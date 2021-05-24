<?php

namespace PHPWarrior;

/**
 * Class Position
 *
 * @package PHPWarrior
 */
class Position
{

    public $floor;
    public static $DIRECTIONS = ['north', 'east', 'south', 'west'];
    public static $RELATIVE_DIRECTIONS = ['forward', 'right', 'backward', 'left'];

    /**
     * Position constructor.
     *
     * @param $floor
     * @param $x
     * @param $y
     * @param null $direction
     */
    public function __construct($floor, $x, $y, $direction = null)
    {
        $this->floor = $floor;
        $this->x = $x;
        $this->y = $y;
        $direction = Position::normalize_direction($direction);
        $this->direction_index = array_search(
            is_null($direction) ? 'north' : $direction,
            self::$DIRECTIONS
        );
    }

    /**
     * @param  int $x
     * @param  int $y
     * @return bool
     */
    public function isAt($x, $y): bool
    {
        return ($this->x == $x && $this->y == $y);
    }

    public function direction()
    {
        return self::$directions[$this->directionIndex];
    }

    /**
     * @param $amount
     */
    public function rotate($amount): void
    {
        $this->directionIndex += $amount;
        
        while ($this->directionIndex > 3) {
            $this->directionIndex -= 4;
        }

        while ($this->directionIndex < 0) {
            $this->directionIndex += 4;
        }
    }

    /**
     * @param  $forward
     * @param  int $right
     * @return mixed
     */
    public function relative_space($forward, int $right = 0): mixed
    {
        return call_user_func_array(
            [$this->floor, 'space'],
            $this->translateOffset($forward, $right)
        );
    }

    public function space(): mixed
    {
        return $this->floor->space($this->x, $this->y);
    }

    /**
     * @param $forward
     * @param int $right
     */
    public function move($forward, int $right = 0): void
    {
        list($this->x, $this->y) = $this->translate_offset($forward, $right);
    }

    public function distanceFromStairs(): mixed
    {
        return $this->distanceOf($this->floor->stairsSpace());
    }

    /**
     * @param  $space
     * @return mixed
     */
    public function distanceOf($space): mixed
    {
        list ($x, $y) = $space->location();
        return abs($this->x - $x) + abs($this->y - $y);
    }

    public function relativeDirectionOfStairs(): mixed
    {
        return $this->relativeDirectionOf($this->floor->stairsSpace());
    }

    /**
     * @param  $space
     * @return mixed
     */
    public function relativeDirectionOf($space): mixed
    {
        return $this->relativeDirection($this->directionOf($space));
    }

    /**
     * @param  $space
     * @return string
     */
    public function directionOf($space): string
    {
        list ($spaceX, $spaceY) = $space->location();
        if (abs($this->x - $spaceX) > abs($this->y - $spaceY)) {
            return $spaceX > $this->x ? 'east' : 'west';
        }

        return $spaceY > $this->y ? 'south' : 'north';
    }

    /**
     * @param  $direction
     * @return mixed
     */
    public function relative_direction($direction): mixed
    {
        $direction = self::normalizeDirection($direction);
        $offset = array_search($direction, self::$directions) - $this->directionIndex;
        
        while ($offset > 3) {
            $offset -= 4;
        }
        
        while ($offset < 0) {
            $offset += 4;
        }

        return self::$relativeDirections[$offset];
    }

    public function translate_offset($forward, $right): array
    {
        $direction = Position::normalizeDirection($this->direction());

        return match ($direction) {
            'north' => [$this->x + (int) $right, $this->y - (int) $forward];
            'east'  => [$this->x + (int) $forward, $this->y + (int) $right];
            'south' => [$this->x - (int) $right, $this->y + (int) $forward];
            'west'  => [$this->x - (int) $forward, $this->y - (int) $right];
        }
    }

    /**
     * Method for normalizing the directionof the position troughout the game.
     */
    public static function normalizeDirection($direction): mixed
    {
        return str_replace(':', '', $direction);
    }

    public function directionStub(): void
    {
        __('north');
        __('east');
        __('west');
        __('south');
        __('forward');
        __('right');
        __('backward');
        __('left');
    }
}
