<?php

namespace PHPWarrior\Units;

/**
 * Class Warrior
 *
 * @package PHPWarrior\Units
 */
class Warrior extends Base
{

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
    public function playTurn($turn)
    {
        $this->player()->playTurn($turn);
    }

    /**
     * Player.
     *
     * @return \Player
     */
    public function player()
    {
        if (!isset($this->player)) {
            $this->player = new \Player();
        }
        return $this->player;
    }

    /**
     * Earn points.
     *
     * @param $points
     */
    public function earnPoints($points)
    {
        $this->score += $points;
        $this->say(sprintf(
            __("earns %s points"),
            $points
        ));
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
     */
    public function name(): mixed
    {
        if ($this->name && !empty($this->name)) {
            return $this->name;
        } else {
            return __('Warrior');
        }
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
    public function performTurn()
    {
        if (is_null($this->currentTurn->action)) {
            $this->say(__("does nothing"));
        }

        return parent::performTurn();
    }
}
