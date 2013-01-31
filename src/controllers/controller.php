<?php namespace openEssayist;


/**
 * 
 * @author Nicolas Van Labeke (https://github.com/vanch3d)
 *
 */
abstract class IController
{
	/**
	 * Shortcut for outputing messages in \Epi debug console 
	 * @param string $msg
	 */
	static protected function debug($msg)
	{
		if (\Epi\Epi::getSetting('debug'))
			\Epi\getDebug()->addMessage(get_called_class(), $msg);
	}
	
	/**
	 * 
	 * @param string $templatename
	 * @param array $params
	 */
	static public function showTemplate($templatename,$params)
	{
		// add the username (if logged in) into the param
		if (\Epi\getSession()->get(Constants::LOGGED_IN) == true)
		{
			$params['username'] = 'username';
		}
		
		// Generate and display the template
		$template = new \Epi\EpiTemplate();
		$template->display($templatename, $params);
	}
	
	/**
	 * 
	 * @param string $task
	 * @return string
	 */
	static protected function getTempDir($task = Null)
	{
		$temp_dir = sys_get_temp_dir();
		$temp_oa_dir = $temp_dir . DIRECTORY_SEPARATOR . "oETemp";
		if (!is_dir($temp_oa_dir))
		{
			$res = mkdir($temp_oa_dir);
		}
		
		if (isset($task))
		{
			$temp_oa_dir = $temp_oa_dir . DIRECTORY_SEPARATOR . $task;
			if (!is_dir($temp_oa_dir))
			{
				$res = mkdir($temp_oa_dir);
			}
		}
		return $temp_oa_dir;
	}
}


