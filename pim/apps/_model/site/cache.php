<?php
namespace model\site;
use db, model, session, request as reqs;
class Cache extends \Origami implements \ArrayAccess
{
	protected $table = 'site_cache';

	protected $primary = 'siteCacheID';

	/**
	 * ORM : Get cache data
	 */
	public function get($key)
	{
		$data = unserialize($this->siteCacheData);


		return isset($data[$key]) ? $data[$key] : null;
	}

	public function offsetGet($key)
	{
		return $this->get($key);
	}

	public function offsetSet($key, $value)
	{
		return $this;
	}

	public function offsetExists($key)
	{

	}

	public function offsetUnset($key)
	{

	}

	public function refreshed()
	{
		if(date('Y-m-d') == date('Y-m-d', strtotime($this->siteCacheUpdatedDate)) && $this->siteCacheQueried == 1)
			return true;

		return false;
	}

	public function persist(array $data)
	{
		$this->siteCacheData = serialize($data);
		$this->siteCacheUpdatedDate = now();
		$this->siteCacheQueried = 1;
		$this->save();
	}

	public function reset()
	{
		$this->siteCacheQueried = 0;
		$this->save();

		return $this;
	}
}