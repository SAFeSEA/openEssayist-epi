<?php namespace openEssayist;

include_once 'controller.php';

class EssayController extends IController
{
	public static function showTask($task)
	{
		if (!self::isUser()) \Epi\getRoute()->redirect('/login');
		
		// get user profile & assignment details
		$apiUser = \Epi\getApi()->invoke('/user/UID.json');
		$apiTask = \Epi\getApi()->invoke('/user/UID/task/' . $task . '.json');
		
		self::debug('$apiTask[\'essays\'] => ' . print_r(array_keys($apiTask['essays'][0]),true));
		
		foreach ($apiTask['essays'] as $key => &$item)
		{
			$apurl = '/user/UID/task/' . $task . '/essay/' . $item['ref'] . '.json';
			$apiEssay = \Epi\getApi()->invoke($apurl);
			$item = array_merge($item,$apiEssay);
		}
		
		self::debug('$apiTask[\'essays\'] => ' . print_r(array_keys($apiTask['essays'][0]),true));
		
		$apiTask['url'] = '/me/task/' . $task;
		self::debug('$apiTask => ' . print_r(array_keys($apiTask),true));
		
		//$params = $apiTask;
		
		
		
		IController::renderTwig('pages/task.html.twig',$apiTask);
	}
	
	
	public static function showReview($task, $essay)
	{
		if (!self::isUser()) \Epi\getRoute()->redirect('/login');
		
		IController::renderTwig('layout.html.twig');
	}

	/**
	 * 
	 */
	public static function showMashup($task, $essay)
	{
		if (!self::isUser()) \Epi\getRoute()->redirect('/login');
		
		IController::renderTwig('layout.html.twig');
	
	}

}