<?php
/**
 * Runtime global cache
 */
class RuntimeCaches
{
	protected static $storage;

	public static function set($key, $value)
	{
		self::$storage[$key] = $value;

		return $this;
	}

	public static function has($key)
	{
		return isset(self::$storage[$key]);
	}

	public static function get($key)
	{
		return self::$storage[$key];
	}
}

?>