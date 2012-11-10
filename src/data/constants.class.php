<?php namespace openEssayist;

/**
 * @author Nicolas Van Labeke (https://github.com/vanch3d)
 *
 */
class Constants
{
	/**
	 * @var unknown
	 */
	const LOGGED_IN = 'logged_in';
}


class Config
{
	const NLTK_SERVER_NAME = "localhost";
	const NLTK_SERVER_PORT = "5000";
	
	static public function NLTK_SERVER()
	{
		return  "http://" . Config::NLTK_SERVER_NAME . ":" . self::NLTK_SERVER_PORT . "/";
	}
}

?>
