<?php

namespace PHPWarrior\Units;

use Player;

/**
 * Class Warrior
 *
 * @package PHPWarrior\Units
 */
class Warrior extends Base
{
    private $name;

    /**
     * Class constructor.
     *
     * @todo make score dynamic
     */
    public function __construct()
    {
        $this->score = 0;
        $this->golemAbilities = [];
    }

    /**
     * Play the turn.
     *
     * @param $turn
     */
    public function playTurn($turn): void
    {
        $this->player()->playTurn($turn);
    }

    /**
     * Player.
     */
    public function player(): mixed
    {
        if (! isset($this->player)) {
            $this->player = new Player();
        }

        return $this->player;
    }

    /**
     * Earn points.
     */
    public function earnPoints($points): void
    {
        $this->score += $points;
        $this->say(sprintf(__("earns %s points"), $points));
    }

    public function attackPower(): int
    {
        return 5;
    }

    /**
     * Set the shooting power.
     */
    public function shootPower(): int
    {
        return 3;
    }

    /**
     * Set the maximum health.
     */
    public function maxHealth(): int
    {
        return 20;
    }

    /**
     * Return the character name.
     *
     * @return mixed
     */
    public function name(): mixed
    {
        if ($this->name && !empty($this->name)) {
            return $this->name;
        }

        return __('Warrior');
    }

    /**
     * Character.
     */
    public function character(): string
    {
        return '@';
    }

    /**
     * Perform the warrior his turn.
     */
    public function performTurn(): mixed
    {
        if (is_null($this->currentTurn->action)) {
            $this->say(__("does nothing"));
        }

        return parent::performTurn();
    }
}
