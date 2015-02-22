<?php

namespace Noodlehaus;

// use Noodlehaus\ConfigInterface;
use Noodlehaus\Exception\ParseException;
use Noodlehaus\Exception\FileNotFoundException;
use Noodlehaus\Exception\UnsupportedFormatException;
use Noodlehaus\Exception\EmptyDirectoryException;
use \Symfony\Component\Yaml\Yaml;

/**
 * Config
 *
 * @package    Config
 * @author     Jesus A. Domingo <jesus.domingo@gmail.com>
 * @author     Hassan Khan <contact@hassankhan.me>
 * @link       https://github.com/noodlehaus/config
 * @license    MIT
 */
abstract class AbstractConfig implements \ArrayAccess, ConfigInterface
{

    /**
     * {@inheritDoc}
     */
    public function get($key, $default = null) {

        // Check if already cached
        if (isset($this->cache[$key])) {
            return $this->cache[$key];
        }

        $segs = explode('.', $key);
        $root = $this->data;

        // nested case
        foreach ($segs as $part) {
            if (isset($root[$part])){
                $root = $root[$part];
                continue;
            }
            else {
                $root = $default;
                break;
            }
        }

        // whatever we have is what we needed
        return ($this->cache[$key] = $root);
    }

    /**
     * {@inheritDoc}
     */
    public function set($key, $value) {

        $segs = explode('.', $key);
        $root = &$this->data;

        // Look for the key, creating nested keys if needed
        while ($part = array_shift($segs)) {
            if (!isset($root[$part]) && count($segs)) {
                $root[$part] = array();
            }
            $root = &$root[$part];
        }

        // Assign value at target node
        $this->cache[$key] = $root = $value;
    }

    /**
     * ArrayAccess Methods
     */

    /**
     * Gets a value using the offset as a key
     *
     * @param  string $offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * Checks if a key exists
     *
     * @param  string $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return !is_null($this->get($offset));
    }

    /**
     * Sets a value using the offset as a key
     *
     * @param  string $offset
     * @param  mixed  $value
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * Deletes a key and its value
     *
     * @param  string $offset
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        $this->set($offset, NULL);
    }

    /**
     * Checks `$path` to see if it is either an array, a directory, or a file
     *
     * @param  string $path
     *
     * @return array
     *
     * @throws EmptyDirectoryException    If `$path` is an empty directory
     */
    protected function _getValidPath($path)
    {
        // If `$path` is array
        if (is_array($path)) {
            $paths = array();
            foreach ($path as $unverifiedPath) {
                $paths = array_merge($paths, $this->_getValidPath($unverifiedPath));
            }
            return $paths;
        }

        // If `$path` is a directory
        if (is_dir($path)) {
            $paths = glob($path . '/*.*');
            if (empty($paths)) {
                throw new EmptyDirectoryException("Configuration directory: [$path] is empty");
            }
            return $paths;
        }

        // If `$path` is a file
        return array($path);
    }

}
