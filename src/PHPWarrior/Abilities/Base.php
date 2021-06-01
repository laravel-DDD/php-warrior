<?php

namespace PHPWarrior\Abilities;

use Exception;
use PHPWarrior\Position;

class Base
{
    public bool $isSense = false;

    public function __construct($unit)
    {
        $this->unit = $unit;
    }

    public function offset($direction, $forward = 1, $right = 0): array
    {
        $direction = Position::normalizeDirection($direction);

        switch ($direction) {
            case 'forward':
                return [$forward, -$right];
                break;
            case 'backward':
                return [-$forward, $right];
                break;
            case 'right':
                return [$right, $forward];
                break;
            case 'left':
                return [-$right, -$forward];
                break;
        }
    }

    public function space($direction, $forward = 1, $right = 0): mixed
    {
        $direction = Position::normalizeDirection($direction);

        return call_user_func_array(
            [$this->unit->position, 'relative_space'],
            $this->offset($direction, $forward, $right)
        );

        //return $this->unit->position->relative_space($this->offset($direction, $forward, $right));
    }

    public function unit($direction, $forward = 1, $right = 0): mixed
    {
        return $this->space($direction, $forward, $right)->unit();
    }

    public function damage($receiver, $amount): void
    {
        $receiver->takeDamage($amount);

        if (!$receiver->isAlive()) {
            $this->unit->earnPoints($receiver->maxHealth());
        }
    }

    public function description()
    {
    }

    public function passTurn()
    {
        # callback which is triggered every turn
    }

    public function verifyDirection($direction): void
    {
        $direction = Position::normalizeDirection($direction);

        if (array_search($direction, Position::$relativeDirections) === false) {
            throw new Exception("Unknown direction {$direction}. Should be :forward, :backward, :left or :right.");
        }
    }

    public static function normalizeDirection($direction)
    {
        return str_replace(':', $direction);
    }
}
