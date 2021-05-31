<?php

namespace PHPWarrior;

use Ulrichsg\Getopt\Getopt;
use Gettext\Translator;

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
        $this->parse_options();
        $this->load_translation();
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
            Config::$path_prefix = $getopt->getOption('d');
        }
        if ($getopt->getOption('l')) {
            Config::$practice_level = $getopt->getOption('l');
        }
        if ($getopt->getOption('s')) {
            Config::$skip_input = true;
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
    public function loadTranslation()
    {
        $translator = new \Gettext\Translator();
        $i18n_path = realpath(__DIR__ . '/../../i18n/' . Config::$locale . '.po');

        if (file_exists($i18n_path)) {
            $translations = \Gettext\Translations::fromPoFile($i18n_path);
            $translator->loadTranslations($translations);
        }

        Translator::initGettextFunctions($translator);
    }
}
