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
		
		$params = $apiTask;
		IController::renderTwig('pages/task.html.twig',$params);
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