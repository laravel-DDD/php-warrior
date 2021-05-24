<?php

namespace PHPWarrior;

use Ulrichsg\Getopt\Getopt;
use Ulrichsg\Getopt\Option;
use Gettext\Translator;
use Gettext\Translations;

/**
 * Class Runner
 *
 * @package PHPWarrior
 */
class Runner
{
    /**
     * Runner constructor.
     *
     * @param $arguments
     * @param $stdin
     * @param $stdout
     */
    public function __construct($arguments, $stdin, $stdout)
    {
        $this->arguments = $arguments;
        $this->stdin = $stdin;
        $this->stdout = $stdout;
        $this->game = new Game();
    }

    /**
     * Run the level.
     */
    public function run()
    {
        Config::$in_stream = $this->stdin;
        Config::$out_stream = $this->stdout;
        Config::$delay = 0.6;
        $this->parseOptions();
        $this->loadTranslation();
        $this->game->start();
    }

    /**
     * Parse the options.
     */
    public function parseOptions()
    {
        $getopt = new Getopt([
            ['d', 'directory', Getopt::REQUIRED_ARGUMENT, 'Run under given directory'],
            ['l', 'level', Getopt::REQUIRED_ARGUMENT, 'Practice level on epic'],
            ['s', 'skip', Getopt::NO_ARGUMENT, 'Skip user input'],
            ['t', 'time', Getopt::REQUIRED_ARGUMENT, 'Delay each turn by seconds'],
            ['L', 'locale', Getopt::REQUIRED_ARGUMENT, 'Specify locale like en_US'],
            ['h', 'help', Getopt::NO_ARGUMENT, 'Show this message'],
        ]);

        try {
            $getopt->parse();
        } catch (\Exception $e) {
            echo $e->getMessage() . "\n";
            exit;
        }

        if ($getopt->getOption('h')) {
            echo $getopt->getHelpText();
            exit;
        }

        if ($getopt->getOption('d')) {
            Config::$pathPrefix = $getopt->getOption('d');
        }

        if ($getopt->getOption('l')) {
            Config::$practiceLevel = $getopt->getOption('l');
        }

        if ($getopt->getOption('s')) {
            Config::$skipInput = true;
        }

        if (!is_null($getopt->getOption('t'))) {
            Config::$delay = $getopt->getOption('t');
        }
        // get locale from $LANG like en_US.UTF8
        list(Config::$locale) = explode('.', getenv('LANG'));
        if ($getopt->getOption('L')) {
            Config::$locale = $getopt->getOption('L');
        }
    }

    /**
     * Load the i12n translation file.
     */
    public function loadTranslation(): void
    {
        $translator = new Translator();
        $i18nPath = realpath(__DIR__ . '/../../i18n/' . Config::$locale . '.po');

        if (file_exists($i18nPath)) {
            $translations = Translations::fromPoFile($i18nPath);
            $translator->loadTranslations($translations);
        }

        Translator::initGettextFunctions($translator);
    }
}
