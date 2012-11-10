<?php namespace Epi;


/**
 * @author  Jaisen Mathai <jaisen@jmathai.com>
 *
 */
class EpiCache_Apc extends EpiCache
{
	private $expiry   = null;


	/**
	 * @param unknown $params
	 */
	public function __construct($params = array())
	{
		$this->expiry   = !empty($params[0]) ? $params[0] : 3600;
	}

	/**
	 * @param unknown $key
	 * @return NULL|unknown|mixed
	 */
	public function get($key)
	{
		if(empty($key)){
			return null;
		}else if($getEpiCache = $this->getEpiCache($key)){
			return $getEpiCache;
		}else{
			$value = apc_fetch($key);
			$this->setEpiCache($key, $value);
			return $value;
		}
	}

	/**
	 * @param string $key
	 * @param string $value
	 * @return boolean
	 */
	public function set($key = null, $value = null)
	{
		if(empty($key) || $value === null)
			return false;

		apc_store($key, $value, $this->expiry);
		$this->setEpiCache($key, $value);
		return true;
	}
}
?>
