<?php  namespace openEssayist;

include_once 'apiclients/APIClient.class.php';

/**
 *
 * @author Nicolas Van Labeke (https://github.com/vanch3d)
 *
 */
class APIEssayAnalyser extends APIClient
{
	private static $SERVER_pEA = "http://localhost:8062";
	
	function __construct() {
		parent::__construct(self::$SERVER_pEA);
	}
	
	
	/**
	 *
	 * @param unknown $text
	 * @return string
	 */
	function getStats($text)
	{
		$resourcePath = "/";
		$method = "GET";
		$queryParams = array();
	
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
	

	/**
	 *
	 * @param unknown $text
	 * @return string
	 */
	function getAnalysis($text)
	{
		$resourcePath = "/api/essay/analysis";
		$method = "POST";
		$queryParams = array();
	
		$queryParams['text'] = $text;
	
		//make the API Call
		$response = "";
		try {
			$response  = $this->callAPI($resourcePath, $method, $queryParams,$queryParams);
	
		} catch (\Exception $e)
		{
			//var_dump($e->getMessage());
			if(\Epi\Epi::getSetting('debug'))
				\Epi\getDebug()->addMessage(__CLASS__, $e->getMessage());
		}
	
		//var_dump($response);
		return $response ;
	}

	/**
	 *
	 * @param unknown $text
	 * @return string
	 */
	function getDispersion($text)
	{
		$resourcePath = "/api/essay/dispersion";
		$method = "POST";
		$queryParams = array();
	
		$queryParams['text'] = $text;
	
		//make the API Call
		$response = "";
		try {
			$response  = $this->callAPI($resourcePath, $method, $queryParams,$queryParams);
	
		} catch (\Exception $e)
		{
			//var_dump($e->getMessage());
			if(\Epi\Epi::getSetting('debug'))
				\Epi\getDebug()->addMessage(__CLASS__, $e->getMessage());
		}
	
		//var_dump($response);
		return $response ;
	}
	
	
}