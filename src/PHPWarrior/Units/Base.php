<?php

namespace PHPWarrior\Units;

use PHPWarrior\Turn;

/**
 * Class Base
 *
 * @package PHPWarrior\Units
 */
class Base
{
    public $position;
    public array $abilities = [];
    public $bound;
    private $health;
    protected $currentTurn;

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
     *
     * @return int
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
     *
     * @return int
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
    public function takeDamage($amount): void
    {
        if ($this->isBound()) {
            $this->unbind();
        }

        if ($this->health()) {
            $this->health -= $amount;
            $this->say(sprintf(__('takes %1$s damage, %2$s health power left'), $amount, $this->health()));

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
        return ! is_null($this->position);
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
    public function bind()
    {
        $this->bound = true;
    }

    /**
     * Say a message.
     *
     * @param $msg
     */
    public function say($msg)
    {
        \PHPWarrior\UI::putsWithDelay("{$this->name()} {$msg}");
    }

    /**
     * name.
     *
     * @return mixed
     */
    public function name()
    {
        $slice_name = explode('\\', get_class($this));
        return array_pop($slice_name);
    }

    /**
     * __ToString function.
     *
     * @return mixed
     */
    public function __ToString()
    {
        return __($this->name());
    }

    /**
     * Add some abilities.
     *
     * @param  string|array $newAbilities
     * @return $this
     */
    public function addAbilities(string|array $newAbilities): self
    {
        foreach ($newAbilities as $abilityStr) {
            $camel = '';
            $abilityStr = str_replace(':', '', $abilityStr);

            foreach (explode('_', $abilityStr) as $str) {
                $camel .= ucfirst($str);
            }
            $className = 'PHPWarrior\Abilities\\' . $camel;
            $this->abilities[$abilityStr] = new $className($this);
        }
        return $this;
    }

    /**
     * Next turn.
     */
    public function nextTurn(): Turn
    {
        return new Turn($this->abilities());
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
    public function performTurn(): mixed
    {
        if ($this->position) {
            foreach ($this->abilities as $ability) {
                $ability->passTurn();
            }

            if ($this->currentTurn->action && !$this->isBound()) {
                [$name, $args] = $this->currentTurn->action;
                call_user_func_array([$this->abilities[$name], 'perform'], $args);
                //$this->abilities[$name]->perform($args);
            }
        }
    }

    /**
     * Play your turn.
     */
    public function playTurn($turn)
    {
        # to be overriden by subclass
    }

    /**
     * Abilities.
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
