<?php

namespace PHPWarrior;

use Exception;

/**
 * Class Turn
 *
 * @package PHPWarrior
 */
class Turn
{
    public $action = null;
    public $senses = [];
    public $abilities;

    /**
     * Turn constructor.
     *
     * @param $abilities
     */
    public function __construct($abilities)
    {
        $this->abilities = $abilities;
    }

    /**
     * @param  $name
     * @param  $arguments
     * @return array
     * @throws Exception
     */
    public function __call($name, $arguments)
    {
        if ($this->action && !$this->abilities[$name]->isSense) {
            throw new Exception(__("Only one action can be performed per turn."));
        }

        if (!$this->abilities[$name]->is_sense) {
            return $this->action = [$name, $arguments];
        }

        return call_user_func_array([$this->abilities[$name], 'perform'], $arguments);
    }
}
