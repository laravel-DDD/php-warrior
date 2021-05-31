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
     * @param  mixed $msg
     * @return mixed
     */
    public static function puts(mixed $msg): mixed
    {
        if (Config::$outStream) {
            fwrite(Config::$out_stream, $msg . PHP_EOL);
        }
    }

    /**
     * @param  $msg
     * @return mixed
     */
    public static function putsWithDelay($msg): mixed
    {
        $result = self::puts($msg);

        if (!is_null(Config::$delay)) {
            usleep(Config::$delay * 1000000);
        }

        return $result;
    }

    /**
     * @param  mixed $msg
     * @return mixed
     */
    public static function put(mixed $msg): mixed
    {
        if (Config::$outStream) {
            fwrite(Config::$outStream, $msg);
        }
    }

    public static function gets()
    {
        if (Config::$inStream) {
            return trim(fgets(Config::$inStream));
        }
    }

    /**
     * @param $msg
     * @return mixed
     */
    public static function request($msg)
    {
        self::put($msg);
        return trim(self::gets());
    }

    /**
     * @param $msg
     * @return bool
     */
    public static function ask($msg)
    {
        fwrite(Config::$outStream, $msg . ' [yn] ');

        return trim(fgets(Config::$inStream)) === 'y';
    }

    /**
     * @param  $item
     * @param  $options
     * @return mixed
     */
    public static function choose($item, $options)
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

            $choice = self::request(sprintf(__("Choose %s by typing the number: "), $item));
            $response = $options[$choice - 1];
        }

        return $response;
    }
}
