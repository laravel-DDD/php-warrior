<?php

namespace PHPWarrior\Units;

/**
 * Class Base
 *
 * @package PHPWarrior\Units
 */
class Base
{

    public $position;
    public $abilities = [];
    public $bound;

    /**
     * The attack power in the base.
     *
     * @return int, Attack power
     */
    public function attackPower(): int
    {
        return 0;
    }

    /**
     * Maximum health.
     */
    public function maxHealth(): int
    {
        return 0;
    }

    /**
     * Earn points.
     *
     * @param $points
     */
    public function earnPoints($points)
    {
        //
    }

    /**
     * Health.
     */
    public function health(): int
    {
        if (!isset($this->health)) {
            $this->health = $this->maxHealth();
        }

        return $this->health;
    }

    /**
     * Take damage.
     *
     * @param $amount
     */
    public function takeDamage($amount)
    {
        if ($this->isBound()) {
            $this->unbind();
        }

        if ($this->health()) {
            $this->health -= $amount;
            $this->say(sprintf(
                __('takes %1$s damage, %2$s health power left'),
                $amount,
                $this->health()
            ));
            if ($this->health() <= 0) {
                $this->position = null;
                $this->say(__("dies"));
            }
        }
    }

    /**
     * Is unit alive?
     */
    public function isAlive(): bool
    {
        return !is_null($this->position);
    }

    /**
     * is_bound?
     */
    public function isBound(): mixed
    {
        return $this->bound;
    }

    /**
     * Unbind
     */
    public function unbind()
    {
        $this->say(__("released from bonds"));
        $this->bound = false;
    }

    /**
     * bind.
     */
    public function bind(): void
    {
        $this->bound = true;
    }

    /**
     * Say a message.
     *
     * @param $msg
     */
    public function say($msg): void
    {
        \PHPWarrior\UI::putsWithDelay("{$this->name()} {$msg}");
    }

    /**
     * name.
     */
    public function name(): mixed
    {
        $sliceName = explode('\\', get_class($this));

        return array_pop($sliceName);
    }

    /**
     * __ToString function.
     */
    public function __ToString(): mixed
    {
        return __($this->name());
    }

    /**
     * Add some abilties.
     *
     * @param $new_abbilities
     * @return $this
     */
    public function addAbilities($newAbbilities): self
    {
        foreach ($newAbbilities as $abbilityStr) {
            $camel = '';
            $abbilityStr = str_replace(':', '', $abbilityStr);

            foreach (explode('_', $abbilityStr) as $str) {
                $camel .= ucfirst($str);
            }

            $className = 'PHPWarrior\Abilities\\' . $camel;
            $this->abilities[$abbilityStr] = new $className($this);
        }

        return $this;
    }

    /**
     * Next turn.
     */
    public function nextTurn(): \PHPWarrior\Turn
    {
        return new \PHPWarrior\Turn($this->abilities());
    }

    /**
     * Prepare for your turn.
     */
    public function prepareTurn()
    {
        $this->currentTurn = $this->nextTurn();

        return $this->playTurn($this->currentTurn);
    }

    /**
     * Perform your turn.
     */
    public function performTurn(): void
    {
        if ($this->position) {
            foreach ($this->abilities as $ability) {
                $ability->passTurn();
            }

            if ($this->currentTurn->action && ! $this->isBound()) {
                list ($name, $args) = $this->currentTurn->action;
                call_user_func_array([$this->abilities[$name], 'perform'], $args);
                //$this->abilities[$name]->perform($args);
            }
        }
    }

    /**
     * Play your turn.
     *
     * @param $turn
     */
    public function playTurn($turn)
    {
        # to be overriden by subclass
    }

    /**
     * Method for getting the array ofabilities that are binded to the unit.
     * In the level that the user is currently playing.
     */
    public function abilities(): array
    {
        return $this->abilities;
    }

    /**
     * The character.
     */
    public function character(): string
    {
        return '?';
    }
}
