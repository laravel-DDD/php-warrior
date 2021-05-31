<?php

namespace PHPWarrior\Units;

/**
 * Class Wizard
 *
 * @package PHPWarrior\Units
 */
class Wizard extends Base
{
    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->addAbilities(['shoot', 'look']);
    }

    public function playTurn($turn): void
    {
        $directions = ['forward', 'left', 'right'];

        foreach ($directions as $direction) {
            foreach ($turn->look($direction) as $space) {
                if ($space->is_player()) {
                    $turn->shoot($direction);
                    return;
                }

                if (! $space->is_empty()) {
                    break;
                }
            }
        }
    }

    /**
     * Shooting power for the wizard.
     */
    public function shootPower(): int
    {
        return 11;
    }

    /**
     * Maximum health for the wizard.
     *
     * @return int
     */
    public function maxHealth(): int
    {
        return 3;
    }

    /**
     * The character.
     */
    public function character(): string
    {
        return "w";
    }
}
