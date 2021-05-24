<?php

namespace PHPWarrior;

/**
 * Class UI
 *
 * @package PHPWarrior
 */
class UI
{
    /**
     * @param $msg
     */
    public static function puts($msg)
    {
        if (Config::$outStream) {
            fwrite(Config::$outStream, $msg . PHP_EOL);
        }
    }

    /**
     * @param $msg
     */
    public static function putsWithDelay($msg)
    {
        $result = self::puts($msg);

        if (!is_null(Config::$delay)) {
            usleep(Config::$delay * 1000000);
        }

        return $result;
    }

    /**
     * @param $msg
     */
    public static function put($msg)
    {
        if (Config::$out_stream) {
            fwrite(Config::$outStream, $msg);
        }
    }

    public static function gets()
    {
        if (Config::$in_stream) {
            return trim(fgets(Config::$inStream));
        }
    }

    /**
     * @param $msg
     * @return mixed
     */
    public static function request($msg): mixed
    {
        self::put($msg);
        return trim(self::gets());
    }

    /**
     * @param $msg
     * @return bool
     */
    public static function ask($msg): bool
    {
        fwrite(Config::$out_stream, $msg . ' [yn] ');

        if (trim(fgets(Config::$inStream)) === 'y') {
            return true;
        }

        return false;
    }

    /**
     * @param  $item
     * @param  $options
     * @return mixed
     */
    public static function choose($item, $options): mixed
    {
        if (count($options) == 1) {
            $response = array_pop($options);
        } else {
            foreach ($options as $i => $option) {
                $num = $i + 1;

                if (is_array($option)) {
                    self::puts("[{$num}] {$option[1]}");
                } else {
                    self::puts("[{$num}] {$option}");
                }
            }

            $choice = self::request(sprintf(
                __("Choose %s by typing the number: "),
                $item
            ));

            $response = $options[$choice - 1];
        }

        return $response;
    }
}
