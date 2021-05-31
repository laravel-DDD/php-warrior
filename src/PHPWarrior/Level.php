<?php

namespace PHPWarrior;

/**
 * Class Level
 *
 * @package PHPWarrior
 */
class Level
{
    public $profile;
    public $number;

    public $description;
    public $tip;
    public $clue;
    public $warrior;
    public $floor;
    public $timeBonus;
    public $aceScore;

    /**
     * @param  $percent
     * @return string
     */
    public static function gradeLetter($percent): string
    {
        if ($percent >= 1.0) {
            return 'S';
        } elseif ($percent >= 0.9) {
            return 'A';
        } elseif ($percent >= 0.8) {
            return 'B';
        } elseif ($percent >= 0.7) {
            return 'C';
        } elseif ($percent >= 0.6) {
            return 'D';
        } else {
            return 'F';
        }
    }

    /**
     * Level constructor.
     *
     * @param $profile
     * @param $number
     */
    public function __construct($profile, $number)
    {
        $this->profile = $profile;
        $this->number = $number;
    }

    public function playerPath(): string
    {
        return $this->profile->playerPath();
    }

    public function loadPath(): string
    {
        return $this->profile->towerPath . '/level_' . sprintf("%03d", $this->number) . '.php';
    }

    public function loadLevel(): void
    {
        $levelLoader = new LevelLoader($this, $this->loadPath());
    }

    public function loadPlayer(): void
    {
        include_once $this->playerPath() . '/player.php';
    }

    public function generatePlayerFiles(): mixed
    {
        $this->load_level();
        $playerGenerator = new PlayerGenerator($this);

        return $playerGenerator->generate();
    }

    public function play(int $turns = 1000): void
    {
        $this->loadLevel();

        foreach (range(0, $turns) as $n) {
            if ($this->isPassed() || $this->isFailed()) {
                return;
            }

            $num = $n + 1;

            UI::puts(sprintf(__("- turn %s -"), $num));
            UI::put($this->floor->character());

            foreach ($this->floor->units() as $unit) {
                $unit->prepareTurn();
            }

            foreach ($this->floor->units() as $unit) {
                $unit->performTurn();
            }

            if ($this->timeBonus > 0) {
                --$this->timeBonus;
            }
        }
    }

    public function tallyPoints(): void
    {
        $score = 0;

        UI::puts(sprintf(__("Level Score: %s"), $this->warrior->score));
        $score += $this->warrior->score;

        UI::puts(sprintf(__("Time Bonus: %s"), $this->time_bonus));
        $score += $this->time_bonus;

        if (count($this->floor->otherUnits()) == 0) {
            UI::puts(sprintf(__("Clear Bonus: %s"), $this->clearBonus()));
            $score += $this->clearBonus();
        }

        if ($this->profile->isEpic()) {
            if ($this->gradeFor($score)) {
                UI::puts(sprintf(__("Level Grade: %s"), $this->gradeFor($score)));
            }

            UI::puts(sprintf(__("Total Score: %s"), $this->scoreCalculation($this->profile->currentEpicScore, $score)));

            if ($this->aceScore) {
                $this->profile->currentEpicGrades[$this->number] = ($score / $this->aceScore);
            }

            $this->profile->currentEpicScore += $score;
        } else {
            UI::puts(sprintf(__("Total Score: %s"), $this->score_calculation($this->profile->score, $score)));

            $this->profile->score += $score;
            $this->profile->abilities = array_keys($this->warrior->abilities);
        }
    }

    /**
     * @param  $score
     * @return string
     */
    public function gradeFor($score): string
    {
        if ($this->aceScore) {
            return self::gradeLetter($score / $this->aceScore);
        }
    }

    public function clearBonus(): float
    {
        return round(($this->warrior->score + $this->timeBonus) * 0.2);
    }

    /**
     * @param  $current_score
     * @param  $addition
     * @return string
     *
     */
    public function scoreCalculation($currentScore, $addition): string
    {
        if (empty($currentScore)) {
            return $addition;
        }

        $total = $current_score + $addition;
        return "{$current_score} + {$addition} = {$total}";
    }

    public function isPassed(): bool
    {
        return $this->floor->stairsSpace()->isWarrior();
    }

    public function isFailed(): bool
    {
        return (! in_array($this->warrior, $this->floor->units(), true));
    }

    public function isExists(): bool
    {
        return file_exists($this->loadPath());
    }

    /**
     * @param  $warrior
     * @return mixed
     */
    public function setupWarrior($warrior): mixed
    {
        $this->warrior = $warrior;
        $this->warrior->addAbilities($this->profile->abilities);
        $this->warrior->name = $this->profile->warriorName;

        return $warrior;
    }
}
