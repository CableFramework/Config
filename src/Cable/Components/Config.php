<?php
/**
 * Created by PhpStorm.
 * User: My
 * Date: 06/04/2017
 * Time: 08:03
 */

namespace Cable\Config;



class Config
{
    /**
     * @var array
     */
    private $configs;

    /**
     * Config constructor.
     * @param array $items
     */
    public function __construct($items = [])
    {
        $this->configs = $items;
    }

    /**
     * Fetch a flattened array of a nested array element.
     *
     * @param  string $key
     * @param mixed $default
     * @return array
     *
     */
    public  function get($key, $default = null)
    {
        $array = $this->configs;

        if (isset($array[$key])) {
            return $array[$key];
        }
        foreach (explode('.', $key) as $segment) {
            if ( ! is_array($array) || ! array_key_exists($segment, $array)) {
                return $default;
            }
            $array = $array[$segment];
        }
        return $array;
    }

    /**
     * @param $key
     * @param $value
     * @return array|mixed
     */
    public  function set($key, $value)
    {
        $array = $this->configs;

        $keys = explode('.', $key);

        while (count($keys) > 1) {
            $key = array_shift($keys);
            // If the key doesn't exist at this depth, we will just create an empty array
            // to hold the next value, allowing us to create the arrays to hold final
            // values at the correct depth. Then we'll keep digging into the array.
            if ( ! isset($array[$key]) || ! is_array($array[$key])) {
                $array[$key] = [];
            }
            $array = &$array[$key];
        }
        $array[array_shift($keys)] = $value;
        return $array;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function delete($name)
    {
        $this->set($name, null);

        return $this;
    }
}
