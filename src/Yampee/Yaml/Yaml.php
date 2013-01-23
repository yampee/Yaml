<?php

/*
 * Yampee Components
 * Open source web development components for PHP 5.
 *
 * @package Yampee Components
 * @author Titouan Galopin <galopintitouan@gmail.com>
 * @link http://titouangalopin.com
 */

/**
 * Offers convenience methods to load and dump YAML.
 *
 * @author Fabien Potencier <fabien.potencier@symfony-project.com>
 */
class Yampee_Yaml_Yaml
{
	/**
	 * Loads YAML into a PHP array.
	 *
	 * The load method, when supplied with a YAML stream (string or file),
	 * will do its best to convert YAML in a file into a PHP array.
	 *
	 *  Usage:
	 *  <code>
	 *   $yaml = new Yampee_Yaml_Yaml();
	 *   $array = $yaml->load('config.yml');
	 *   print_r($array);
	 *  </code>
	 *
	 * @param string $input Path of YAML file or string containing YAML
	 *
	 * @return array The YAML converted to a PHP array
	 *
	 * @throws InvalidArgumentException If the YAML is not valid
	 */
	public function load($input)
	{
		$file = '';

		// if input is a file, process it
		if (strpos($input, "\n") === false && is_file($input)) {
			$file = $input;

			ob_start();
			$retval = include($input);
			$content = ob_get_clean();

			// if an array is returned by the config file assume it's in plain php form else in YAML
			$input = is_array($retval) ? $retval : $content;
		}

		// if an array is returned by the config file assume it's in plain php form else in YAML
		if (is_array($input)) {
			return $input;
		}

		$yaml = new Yampee_Yaml_Parser();

		try {
			$ret = $yaml->parse($input);
		} catch (Exception $e) {
			throw new InvalidArgumentException(sprintf('Unable to parse %s: %s', $file ? sprintf('file "%s"', $file) : 'string', $e->getMessage()));
		}

		return $ret;
	}

	/**
	 * Dumps a PHP array to a YAML string.
	 *
	 * The dump method, when supplied with an array, will do its best
	 * to convert the array into friendly YAML.
	 *
	 * @param array   $array PHP array
	 * @param integer $inline The level where you switch to inline YAML
	 *
	 * @return string A YAML string representing the original PHP array
	 */
	public function dump($array, $inline = 2)
	{
		$yaml = new Yampee_Yaml_Dumper();

		return $yaml->dump($array, $inline);
	}
}
