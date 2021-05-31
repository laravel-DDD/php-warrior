<?php

namespace PHPWarrior;

class Profile
{
    public $score;

    public bool $epic = false;

    public $epicScore;
    public $currentEpicScore;
    public $averageGrade;

    public array $currentEpicGrades = [];
    public array $abilities = [];
    public int|null $levelNumber = 0;
    public int|null $lastLevelNumber;
    public string $towerPath = '';
    public string $warriorName;
    public string $playerPath;
    public string $tower;

    public static function encode($obj): string
    {
        return serialize($obj);
    }

    /**
     * Save
     */
    public function save(): void
    {
        // update_epic_score
        // @level_number = 0 if epic?
        file_put_contents($this->playerPath() . '/.pwprofile', self::encode($this));
    }

    /**
     * Decode a string.
     */
    public static function decode(string $str): mixed
    {
        // The build in php unserialize method options has the allowed_classes in the options.
        // Because the php object obfuscation security bug in php itself. With the options.
        // We prevent the security issue form happening.

        return unserialize($str, ['allowed_classes' => true]);
    }

    /**
     * load
     *
     * @param  $path
     * @return mixed
     */
    public static function load(string $path): mixed
    {
        return self::decode(file_get_contents($path));
    }

    /**
     * The pad to the player.php file.
     *
     * @return string
     */
    public function playerPath(): string
    {
        if (!$this->playerPath) {
            $this->playerPath = realpath(Config::$pathPrefix) . '/phpwarrior/' . $this->directory_name();
        }

        return $this->playerPath;
    }

    /**
     * The directory name
     */
    public function directoryName(): string
    {
        return implode('-', [strtolower($this->warriorName), $this->tower()->name()]);
    }

    public function __ToString()
    {
        return implode('-', [
            $this->warriorName,
            $this->tower->name(),
            __("Level") . ' ' . $this->levelNumber,
            __("score") . ' ' . $this->score
        ]);
    }

    /**
     * Epic score with grade.
     */
    public function epicScoreWithGrade(): string
    {
        if ($this->averageGrade) {
            $letter = Level::grade_letter($this->averageGrade);

            return "{$this->epicScore} ({$letter})";
        }

        return $this->epicScore;
    }

    /**
     * Tower.
     */
    public function tower(): Tower
    {
        if (! $this->tower) {
            $this->tower = new Tower($this->towerPath);
        }
        return $this->tower;
    }

    /**
     * The current level.
     */
    public function currentLevel(): Level
    {
        return new Level($this, $this->levelNumber);
    }

    /**
     * The next level.
     */
    public function nextLevel(): Level
    {
        return new Level($this, $this->level_number + 1);
    }

    public function enableEpicMode(): void
    {
        $this->epic = true;
        $this->epicScore = 0;
        $this->currentEpicScore = 0;
        $this->lastLevelNumber = $this->levelNumber;
    }

    public function enableNormalMode(): void
    {
        $this->epic = false;
        $this->epicScore = 0;
        $this->currentEpicScore = 0;
        $this->currentEpicGrades = [];
        $this->averageGrade = null;
        $this->levelNumber = $this->lastLevelNumber;
        $this->lastLevelNumber = null;
    }

    public function isEpic(): bool
    {
        return $this->epic;
    }

    public function isLevelAfterEpic(): bool
    {
        if ($this->lastLevelNumber) {
            $level = new Level($this, $this->lastLevelNumber + 1);

            return $level->isExists();
        }

        return false;
    }

    public function updateEpicScore(): void
    {
        if ($this->currentEpicScore > $this->epicScore) {
            $this->epicScore = $this->currentEpicScore;
            $this->averageGrade = $this->calculateAverageGrade();
        }
    }

    public function calculateAverageGrade()
    {
        if (count($this->currentEpicGrades) > 0) {
            $sum = array_sum($this->currentEpicGrades);
            return $sum / count($this->currentEpicGrades);
        }
    }
}
