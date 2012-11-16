<?php namespace openEssayist;

include_once 'apiclients/APIClient.class.php';

/**
 *
 * @author Nicolas Van Labeke (https://github.com/vanch3d)
 *
 */
class APISpellCheck extends APIClient
{
	private static $SERVER_ADT_REMOTE = "http://service.afterthedeadline.com";
	private static $SERVER_ADT_LOCAL = "http://localhost:1049";
	
	function __construct() {
		parent::__construct(self::$SERVER_ADT_REMOTE);
	}


	protected function full_url()
	{
		$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
		$sp = strtolower($_SERVER["SERVER_PROTOCOL"]);
		$protocol = substr($sp, 0, strpos($sp, "/")) . $s;
		$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
		return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
	}

	/**
	 *
	 * @return string
	 */
	protected function getKey()
	{
		$ret = "OESS-" . md5($this->full_url());
		var_dump($ret);
		return $ret;
	}



	/**
	 *
	 * @param unknown $text
	 * @return string
	 */
	function getStats($text)
	{
		$resourcePath = "/stats";
		$method = "GET";
		$queryParams = array();

		$queryParams['key'] = $this->getKey();;
		$queryParams['data'] = "test the connection";//$text;

		//make the API Call
		$response = "";
		try {
			$response  = $this->callAPI($resourcePath, $method, $queryParams,$queryParams);

		} catch (\Exception $e)
		{
			if(\Epi\Epi::getSetting('debug'))
				\Epi\getDebug()->addMessage(__CLASS__, $e->getMessage());
		}

		return $response ;
	}


	function getSpelling($text)
	{
		$resourcePath = "/checkDocument";
		$method = "GET";
		$queryParams = array();

		$queryParams['data'] = $text;
		$queryParams['key'] = $text;

		//make the API Call
		$response = "";
		try {
			$response  = $this->callAPI($resourcePath, $method, $queryParams);

		} catch (\Exception $e)
		{
			if(\Epi\Epi::getSetting('debug'))
				\Epi\getDebug()->addMessage(__CLASS__, $e->getMessage());
		}

		return $response ;
	}

	function getGrammar($text)
	{
		$resourcePath = "/checkGrammar";
		$method = "GET";
		$queryParams = array();

		$queryParams['data'] = $text;
		$queryParams['key'] = $text;

		//make the API Call
		$response = "";
		try {
			$response  = $this->callAPI($resourcePath, $method, $queryParams);

		} catch (\Exception $e)
		{
			if(\Epi\Epi::getSetting('debug'))
				\Epi\getDebug()->addMessage(__CLASS__, $e->getMessage());
		}

		return $response ;
	}

}